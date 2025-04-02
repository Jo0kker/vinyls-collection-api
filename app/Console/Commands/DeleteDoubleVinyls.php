<?php

namespace App\Console\Commands;

use App\Models\CollectionVinyl;
use App\Models\Search;
use App\Models\Trade;
use App\Models\Vinyl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteDoubleVinyls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-double-vinyls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime les vinyles en double en préservant les relations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Début de la suppression des vinyles en double...');

        // 1. Supprimer les doublons de collection_vinyls par user
        $this->info('Suppression des doublons dans les collections...');
        $collectionDuplicates = CollectionVinyl::select('user_id', 'vinyl_id')
            ->whereNull('deleted_at')
            ->groupBy('user_id', 'vinyl_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($collectionDuplicates as $duplicate) {
            $collectionVinyls = CollectionVinyl::where('user_id', $duplicate->user_id)
                ->where('vinyl_id', $duplicate->vinyl_id)
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->get();

            // Garder le plus récent, supprimer les autres
            $keepVinyl = $collectionVinyls->first();
            $deleteVinyls = $collectionVinyls->slice(1);

            foreach ($deleteVinyls as $vinyl) {
                $vinyl->delete();
                $this->info("Suppression du doublon collection_vinyl {$vinyl->id} pour l'utilisateur {$duplicate->user_id}");
            }
        }

        // 2. Supprimer les doublons de trades par user
        $this->info('Suppression des doublons dans les trades...');
        $tradeDuplicates = Trade::select('user_id', 'vinyl_id')
            ->groupBy('user_id', 'vinyl_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($tradeDuplicates as $duplicate) {
            $trades = Trade::where('user_id', $duplicate->user_id)
                ->where('vinyl_id', $duplicate->vinyl_id)
                ->orderBy('id', 'desc')
                ->get();

            // Garder le plus récent, supprimer les autres
            $keepTrade = $trades->first();
            $deleteTrades = $trades->slice(1);

            foreach ($deleteTrades as $trade) {
                $trade->delete();
                $this->info("Suppression du doublon trade {$trade->id} pour l'utilisateur {$duplicate->user_id}");
            }
        }

        // 3. Supprimer les doublons de searches par user
        $this->info('Suppression des doublons dans les recherches...');
        $searchDuplicates = Search::select('user_id', 'vinyl_id')
            ->groupBy('user_id', 'vinyl_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($searchDuplicates as $duplicate) {
            $searches = Search::where('user_id', $duplicate->user_id)
                ->where('vinyl_id', $duplicate->vinyl_id)
                ->orderBy('id', 'desc')
                ->get();

            // Garder le plus récent, supprimer les autres
            $keepSearch = $searches->first();
            $deleteSearches = $searches->slice(1);

            foreach ($deleteSearches as $search) {
                $search->delete();
                $this->info("Suppression du doublon search {$search->id} pour l'utilisateur {$duplicate->user_id}");
            }
        }

        // 4. Supprimer les vinyles en double (code existant)
        $this->info('Suppression des vinyles en double...');
        $duplicates = Vinyl::select('discog_id')
            ->whereNotNull('discog_id')
            ->groupBy('discog_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $count = 0;
        foreach ($duplicates as $duplicate) {
            $vinyls = Vinyl::where('discog_id', $duplicate->discog_id)
                ->orderBy('id', 'desc')
                ->get();

            // Le premier vinyl (le plus récent) sera conservé
            $keepVinyl = $vinyls->first();
            $deleteVinyls = $vinyls->slice(1);

            DB::beginTransaction();
            try {
                foreach ($deleteVinyls as $vinyl) {
                    // Transférer les relations collection_vinyls
                    CollectionVinyl::where('vinyl_id', $vinyl->id)
                        ->update(['vinyl_id' => $keepVinyl->id]);

                    // Transférer les relations trades
                    Trade::where('vinyl_id', $vinyl->id)
                        ->update(['vinyl_id' => $keepVinyl->id]);

                    // Transférer les relations searches
                    Search::where('vinyl_id', $vinyl->id)
                        ->update(['vinyl_id' => $keepVinyl->id]);

                    // Supprimer le vinyl
                    $vinyl->delete();
                    $count++;
                }

                DB::commit();
                $this->info("Traitement des doublons pour discog_id: {$duplicate->discog_id}");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Erreur lors du traitement des doublons pour discog_id: {$duplicate->discog_id}");
                $this->error($e->getMessage());
            }
        }

        $this->info("Traitement terminé. {$count} vinyles en double ont été supprimés.");
    }
}
