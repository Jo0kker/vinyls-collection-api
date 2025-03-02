<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('collection_vinyls', function (Blueprint $table) {
            // Index pour les recherches par collection
            $table->index(['collection_id', 'created_at']);

            // Index pour les recherches par vinyl
            $table->index(['vinyl_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::table('collection_vinyls', function (Blueprint $table) {
            $table->dropIndex(['collection_id', 'created_at']);
            $table->dropIndex(['vinyl_id', 'created_at']);
        });
    }
};
