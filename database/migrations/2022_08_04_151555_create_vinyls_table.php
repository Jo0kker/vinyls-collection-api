<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vinyls', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('artist')->nullable();
            $table->string('genre')->nullable();
            $table->string('image')->nullable();
            $table->json('track_list')->nullable();
            $table->string('released')->nullable();
            $table->string('provenance')->nullable();
            $table->integer('discog_id')->nullable();
            $table->string('discog_url')->nullable();
            $table->json('discog_videos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vinyls');
    }
};
