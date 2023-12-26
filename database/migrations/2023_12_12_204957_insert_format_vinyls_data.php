<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $format_vinyls = [
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

        foreach ($format_vinyls as $format_vinyl) {
            DB::table('format_vinyls')->insert([
                'name' => $format_vinyl,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
