<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('collection_vinyls', function (Blueprint $table) {
            $table->dropForeign('collection_vinyls_format_vinyl_id_foreign');
            $table->dropColumn('format_vinyl_id');
            $table->string('format')->nullable();
        });

        Schema::table('searches', function (Blueprint $table) {
            $table->dropForeign('searches_format_vinyl_id_foreign');
            $table->dropColumn('format_vinyl_id');
            $table->string('format')->nullable();
        });

        Schema::table('trades', function (Blueprint $table) {
            $table->dropForeign('trades_format_vinyl_id_foreign');
            $table->dropColumn('format_vinyl_id');
            $table->string('format')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_vinyls', function (Blueprint $table) {
            $table->dropColumn('format');
            $table->foreignId('format_vinyl_id')->nullable()->constrained('format_vinyls');
        });

        Schema::table('searches', function (Blueprint $table) {
            $table->dropColumn('format');
            $table->foreignId('format_vinyl_id')->nullable()->constrained('format_vinyls');
        });

        Schema::table('trades', function (Blueprint $table) {
            $table->dropColumn('format');
            $table->foreignId('format_vinyl_id')->nullable()->constrained('format_vinyls');
        });
    }
};
