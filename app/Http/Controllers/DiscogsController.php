<?php

namespace App\Http\Controllers;

use App\Services\DiscogsDataMapper;
use App\Services\DiscogsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Jobs\ImportCollectionsJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use romanzipp\QueueMonitor\Models\Monitor;


class DiscogsController extends Controller
{
    public function __construct(
        private readonly DiscogsDataMapper $discogsDataMapper
    ) {}

    public function search(Request $request): JsonResponse
    {
        $discogs = new DiscogsService();
        // Recherche par code Discogs (recherche unique)
        if ($request->input('discog_code')) {
            $discog_code = $request->input('discog_code');
            $type = substr($discog_code, 1, 1);
            $type = $type === 'm' ? 'masters' : 'releases';
            $id = substr($discog_code, 2, -1);
            $discog = $discogs->getVinylDataById(id: $id, type: $type);
            $discog = $this->discogsDataMapper->mapData($discog);

            $data = (object) [
                'data' => [$discog],
                'current_page' => 1,
                'from' => 1,
                'last_page' => 1,
                'per_page' => 1,
                'to' => 1,
                'total' => 1,
            ];

            return response()->json($data);
        }

        // Préparation des paramètres de recherche
        $searchParams = [
            'title' => $request->input('title'),
            'artist' => $request->input('artist'),
            'year' => $request->input('year'),
            'page' => $request->input('page') ?? 1,
            'perPage' => $request->input('per_page') ?? 10,
        ];

        // Recherche des masters et des releases
        try {
            $searchMasters = $discogs->search(...array_merge($searchParams, ['type' => 'master']));
        } catch (\Throwable $th) {
            $searchMasters = null;
        }
        try {
            $searchReleases = $discogs->search(...array_merge($searchParams, ['type' => 'release']));
        } catch (\Throwable $th) {
            $searchReleases = null;
        }
        // Calcul de la pagination
        $totalPages = max($searchMasters->pagination?->pages ?? 0, $searchReleases->pagination?->pages ?? 0);
        $currentPage = max($searchMasters?->pagination?->page ?? 1, $searchReleases?->pagination?->page ?? 1);
        $perPage = $searchMasters->pagination->per_page ?? $searchReleases->pagination->per_page ?? 10;
        // Fusion et mappage des résultats
        $combinedResults = array_merge($searchMasters->results ?? [], $searchReleases->results ?? []);
        $mappedResults = array_map(function ($item) {
            return $this->discogsDataMapper->mapData($item);
        }, $combinedResults);

        // Construction de l'objet de réponse avec la pagination
        $data = (object) [
            'data' => $mappedResults,
            'current_page' => $currentPage,
            'last_page' => $totalPages,
            'per_page' => $perPage,
        ];

        return response()->json($data);
    }

    public function importCollections(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->discogs_token) {
            return response()->json(['error' => 'Compte Discogs non lié'], 400);
        }

        // Vérifier si un job est déjà en cours pour cet utilisateur
        if (ImportCollectionsJob::isRunning($user)) {
            $runningJob = Monitor::query()
                ->where('name', str_replace('/', '\\', ImportCollectionsJob::class))
                ->whereRaw("(jsonb_extract_path_text(data::jsonb, 'user_id'))::integer = ?", [$user->id])
                ->whereNull('finished_at')
                ->first();

            return response()->json([
                'error' => 'Une importation est déjà en cours',
                'job_uuid' => $runningJob->job_uuid
            ], 409);
        }

        ImportCollectionsJob::dispatch($user);

        // On attend un peu que le job soit créé dans la base
        sleep(1);

        $monitor = Monitor::query()
            ->where('name', str_replace('/', '\\', ImportCollectionsJob::class))
            ->whereRaw("(jsonb_extract_path_text(data::jsonb, 'user_id'))::integer = ?", [$user->id])
            ->orderBy('started_at', 'desc')
            ->first();

        return response()->json([
            'message' => 'L\'importation est en cours. Vous recevrez un email une fois terminé.',
            'job_uuid' => $monitor->job_uuid
        ]);
    }

    public function importStatus(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $query = Monitor::query()
            ->where('name', str_replace('/', '\\', ImportCollectionsJob::class))
            ->whereRaw("(jsonb_extract_path_text(data::jsonb, 'user_id'))::integer = ?", [$user->id]);

        // Si un job_uuid est fourni, on cherche spécifiquement ce job
        if ($request->has('job_uuid')) {
            $query->where('job_uuid', $request->input('job_uuid'));
        }

        $monitor = $query->orderBy('started_at', 'desc')->first();

        if (!$monitor) {
            return response()->json(['status' => 'no_job']);
        }

        $status = [
            'job_uuid' => $monitor->job_uuid,
            'status' => match (true) {
                $monitor->hasFailed() => 'failed',
                $monitor->isFinished() => 'completed',
                default => 'running'
            },
            'progress' => $monitor->progress,
            'started_at' => $monitor->getStartedAtExact(),
            'finished_at' => $monitor->getFinishedAtExact(),
            'remaining_seconds' => $monitor->getRemainingSeconds(),
            'remaining_interval' => $monitor->getRemainingInterval()?->forHumans(),
            'data' => $monitor->getData()
        ];

        if ($monitor->hasFailed()) {
            $status['error'] = $monitor->exception_message;
        }

        return response()->json(['data' => $status]);
    }
}
