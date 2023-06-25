<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // add permission in permissions table named telescope
        DB::table('permissions')->insert([
            'name' => 'view telescope',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // remove permission in permissions table named telescope
        DB::table('permissions')->where('name', 'view telescope')->delete();
    }
};
