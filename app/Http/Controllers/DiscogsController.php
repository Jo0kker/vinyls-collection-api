<?php

namespace App\Http\Controllers;

use App\Services\DiscogsDataMapper;
use App\Services\DiscogsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Collection;
use App\Models\Vinyl;
use App\Models\CollectionVinyl;

class DiscogsController extends Controller
{
    public function __construct(
        private readonly DiscogsDataMapper $discogsDataMapper
    ) {
    }

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

    public function syncCollections(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user->discogs_token) {
            return response()->json(['error' => 'Compte Discogs non lié'], 400);
        }

        $discogs = new DiscogsService();

        try {
            $folders = $discogs->getUserFolders(
                $user->discogs_token,
                $user->discogs_token_secret,
                $user->discogs_username
            );

            $syncedCollections = 0;
            foreach ($folders as $folder) {
                // Ignorer le dossier "All" (id = 0)
                if ($folder->id === 0) {
                    continue;
                }

                // Gérer le dossier "Uncategorized" (id = 1)
                if ($folder->id === 1) {
                    $collection = Collection::firstOrCreate(
                        [
                            'user_id' => $user->id,
                            'name' => 'Non catégorisé'
                        ],
                        [
                            'description' => 'Vinyles non catégorisés (importés depuis Discogs)',
                            'discogs_folder_id' => $folder->id
                        ]
                    );
                } else {
                    $collection = Collection::firstOrCreate(
                        [
                            'user_id' => $user->id,
                            'discogs_folder_id' => $folder->id
                        ],
                        [
                            'name' => $folder->name,
                            'description' => 'Importé depuis Discogs'
                        ]
                    );
                }

                if ($folder->count > 0) {
                    $folderItems = $discogs->getFolderItems(
                        $user->discogs_token,
                        $user->discogs_token_secret,
                        $user->discogs_username,
                        $folder->id
                    );
                    
                    foreach ($folderItems as $item) {
                        $vinyl = Vinyl::firstOrCreate(
                            ['discog_id' => $item->id],
                            $this->discogsDataMapper->mapData($item)
                        );

                        CollectionVinyl::firstOrCreate([
                            'collection_id' => $collection->id,
                            'vinyl_id' => $vinyl->id
                        ]);
                    }
                }
                $syncedCollections++;
            }

            return response()->json([
                'message' => 'Collections synchronisées avec succès',
                'collections_count' => $syncedCollections
            ]);

        } catch (Exception $e) {
            Log::error('Discogs sync error:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
