<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vinyls', function (Blueprint $table) {
            // Index pour les recherches par statut et date
            $table->index(['title']);
        });
    }

    public function down()
    {
        Schema::table('trades', function (Blueprint $table) {
            $table->dropIndex(['title'
            ]);
        });
    }
};
