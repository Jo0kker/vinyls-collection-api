<?php

namespace App\Jobs;

use App\Models\Collection;
use App\Models\CollectionVinyl;
use App\Models\User;
use App\Models\Vinyl;
use App\Services\DiscogsDataMapper;
use App\Services\DiscogsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use romanzipp\QueueMonitor\Traits\IsMonitored;
use romanzipp\QueueMonitor\Models\Monitor;

class ImportCollectionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    private $user;
    public $timeout = 3600;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function isRunning(User $user): bool
    {
        return Monitor::query()
            ->where('name', str_replace('/', '\\', self::class))
            ->whereRaw("(jsonb_extract_path_text(data::jsonb, 'user_id'))::integer = ?", [$user->id])
            ->whereNull('finished_at')
            ->orderBy('started_at', 'desc')
            ->exists();
    }

    public function queueMonitorName(): string
    {
        return static::class . ':' . $this->user->id;
    }

    public function initialMonitorData(): array
    {
        return [
            'user_id' => (string) $this->user->id,
            'total_folders' => 0,
            'processed_folders' => 0,
            'username' => $this->user->username ?? $this->user->email
        ];
    }

    public function handle(DiscogsService $discogsService, DiscogsDataMapper $discogsDataMapper)
    {
        try {
            $folders = $discogsService->getUserFolders(
                $this->user->discogs_token,
                $this->user->discogs_token_secret,
                $this->user->discogs_username
            );

            $totalFolders = count($folders) - 1;
            $totalVinyls = 0;
            foreach ($folders as $folder) {
                if ($folder->id !== 0) {
                    $totalVinyls += $folder->count;
                }
            }

            $this->queueData([
                'total_folders' => $totalFolders,
                'total_vinyls' => $totalVinyls,
                'imported_vinyls' => 0,
                'imported_folders' => 0,
                'current_folder' => '',
                'current_folder_progress' => 0
            ], true);
            $this->queueProgress(0);

            $syncedCollections = 0;
            $totalImportedVinyls = 0;

            foreach ($folders as $folder) {
                if ($folder->id === 0) {
                    continue;
                }

                $this->queueData([
                    'current_folder' => $folder->name,
                    'current_folder_progress' => 0
                ], true);

                $collection = Collection::firstOrCreate(
                    [
                        'user_id' => $this->user->id,
                        'discogs_folder_id' => $folder->id
                    ],
                    [
                        'name' => $folder->id === 1 ? 'Non catégorisé' : $folder->name,
                        'description' => 'Importé depuis Discogs',
                        'user_id' => $this->user->id
                    ]
                );

                if ($folder->count > 0) {
                    $perPage = 50;
                    $totalPages = ceil($folder->count / $perPage);
                    $folderImportedVinyls = 0;

                    for ($page = 1; $page <= $totalPages; $page++) {
                        $folderItemsResponse = $discogsService->getFolderItems(
                            $this->user->discogs_token,
                            $this->user->discogs_token_secret,
                            $this->user->discogs_username,
                            $folder->id,
                            $page,
                            $perPage
                        );

                        $folderItems = $folderItemsResponse->releases ?? [];
                        foreach ($folderItems as $item) {
                            // Vérifier si le vinyle existe déjà
                            $vinyl = Vinyl::where('discog_id', $item->id)
                                ->where('type', "releases")
                                ->first();
                            if (!$vinyl) {
                                // Si le vinyle n'existe pas, récupérer les données et l'ajouter
                                $completeData = $discogsService->getVinylDataById($item->id, 'releases');
                                $vinylData = $discogsDataMapper->mapData($completeData);
                                $vinyl = Vinyl::create($vinylData);
                            }

                            CollectionVinyl::firstOrCreate([
                                'collection_id' => $collection->id,
                                'vinyl_id' => $vinyl->id,
                                'user_id' => $this->user->id
                            ]);

                            $folderImportedVinyls++;
                            $totalImportedVinyls++;

                            // Mise à jour des progressions
                            $folderProgress = ($folderImportedVinyls / $folder->count) * 100;
                            $totalProgress = ($totalImportedVinyls / $totalVinyls) * 100;

                            $this->queueData([
                                'imported_vinyls' => $totalImportedVinyls,
                                'current_folder_progress' => round($folderProgress, 1)
                            ], true);
                            $this->queueProgress(round($totalProgress, 1));
                        }
                    }
                }

                $syncedCollections++;
                $this->queueData([
                    'imported_folders' => $syncedCollections,
                    'processed_folders' => $syncedCollections
                ], true);
            }

            $this->queueProgress(100);
            $this->queueData([
                'current_folder' => 'Terminé',
                'current_folder_progress' => 100
            ], true);

            // Envoyer un email une fois l'importation terminée
            Mail::to($this->user->email)->send(new \App\Mail\ImportCompleted($syncedCollections));
        } catch (Exception $e) {
            Log::error('Discogs sync error:', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Exception $exception): void
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }
}
