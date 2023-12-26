<?php

namespace App\Services;

/**
 * TO map data from discogs to vinyl model
 */
class DiscogsDataMapper
{
    /// discogs_field => vinyl_field
    private $mappingField = [
        'id' => 'discog_id',
        'title' => 'title',
        'artists' => 'artist',
        'genres' => 'genre',
        'images' => 'discog_img',
        'tracklist' => 'track_list',
        'year' => 'released',
        'country' => 'provenance',
        'videos' => 'discog_videos',
        'uri' => 'discog_url',
        'type' => 'type',
    ];

    public function mapData($discogsData): array
    {
        $vinyl = [];

        // Boucle sur toutes les données de Discogs
        foreach ($discogsData as $discogsField => $value) {
            // Détermine le champ de sortie en vérifiant si une correspondance existe dans $mappingField
            $vinylField = $this->mappingField[$discogsField] ?? $discogsField;

            // Applique la transformation si nécessaire
            switch ($vinylField) {
                case 'genre':
                    $vinyl[$vinylField] = implode(', ', $value);
                    break;
                case 'discog_img':
                    $vinyl['image'] = $value[0]->uri ?? null;
                    $vinyl[$vinylField] = json_encode($value);
                    break;
                case 'track_list':
                    $vinyl[$vinylField] = json_encode($value);
                    break;
                case 'discog_videos':
                    $vinyl[$vinylField] = json_encode($value);
                    break;
                case 'artist':
                    $artists = [];
                    foreach ($value as $artist) {
                        $artists[] = $artist->name ?? '';
                    }
                    $vinyl[$vinylField] = implode(', ', $artists);
                    break;
                default:
                    // Assignation directe du champ (renommé si nécessaire)
                    $vinyl[$vinylField] = $value;
                    break;
            }
        }

        return $vinyl;
    }
}
