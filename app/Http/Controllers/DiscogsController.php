<?php

namespace App\Http\Controllers;

use App\Services\DiscogsDataMapper;
use App\Services\DiscogsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
