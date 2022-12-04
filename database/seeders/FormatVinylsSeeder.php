<?php

namespace Database\Seeders;

use App\Models\FormatVinyls;
use Illuminate\Database\Seeder;

class FormatVinylsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formats = [
            'LP',
            '45T',
            'Maxi 45T',
            'EP',
            '2LP',
            '3LP',
            '4LP',
            '5LP',
            '6LP',
            '78T',
            'LP 25cm',
            'Maxi 33T',
            'CD',
            'K7',
            'DVD',
            'VHS',
            'Autre',
        ];

        foreach ($formats as $format) {
            FormatVinyls::create([
                'name' => $format,
            ]);
        }
    }
}
