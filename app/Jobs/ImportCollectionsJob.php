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

class ImportCollectionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    public $timeout = 3600;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(DiscogsService $discogsService, DiscogsDataMapper $discogsDataMapper)
    {
        dump($this->user->id);
        try {
            $folders = $discogsService->getUserFolders(
                $this->user->discogs_token,
                $this->user->discogs_token_secret,
                $this->user->discogs_username
            );

            $syncedCollections = 0;
            foreach ($folders as $folder) {
                if ($folder->id === 0) {
                    continue;
                }

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
                    $folderItems = $discogsService->getFolderItems(
                        $this->user->discogs_token,
                        $this->user->discogs_token_secret,
                        $this->user->discogs_username,
                        $folder->id
                    );

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
                    }
                }
                $syncedCollections++;
            }

            // Envoyer un email une fois l'importation terminée
            Mail::to($this->user->email)->send(new \App\Mail\ImportCompleted($syncedCollections));

        } catch (Exception $e) {
            Log::error('Discogs sync error:', ['error' => $e->getMessage()]);
        }
    }
} 