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
        Schema::create('collection_vinyls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained();
            $table->foreignId('vinyl_id')->constrained();
            $table->foreignId('format_vinyl_id')->nullable()->constrained();
            $table->integer('cover_state');
            $table->string('year_purchased')->nullable();
            $table->double('price')->nullable();
            $table->boolean('is_sellable')->default(false);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('collection_vinyls');
    }
};
