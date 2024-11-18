<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string(column: 'password')->nullable()->change();
            $table->string('discogs_id')->nullable();
            $table->string('discogs_token')->nullable();
            $table->string('discogs_token_secret')->nullable();
            $table->string('discogs_username')->nullable();
            $table->string('discogs_avatar')->nullable();
            $table->json('discogs_data')->nullable(); // Pour stocker d'autres données potentielles
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
            $table->dropColumn([
                'discogs_id',
                'discogs_token',
                'discogs_token_secret',
                'discogs_username',
                'discogs_avatar',
                'discogs_data'
            ]);
        });
    }
};
