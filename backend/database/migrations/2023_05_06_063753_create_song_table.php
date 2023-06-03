<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song', function (Blueprint $table) {
            $table->id();
            $table->string('s_id');
            $table->string('title');
            $table->text('image')->default('default.png');
            $table->text('audio');
            $table->time('duration');
            $table->date('release_date');
            $table->enum('status', ['pending', 'published', 'rejected']);
            $table->unsignedBigInteger('artist_id');
            $table->unsignedBigInteger('album_id')->nullable()->default(null);
            $table->unsignedBigInteger('genre_id');
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->foreign('artist_id')->references('id')->on('artist');
            $table->foreign('album_id')->references('id')->on('album')->onDelete('set null');
            $table->foreign('genre_id')->references('id')->on('genre');
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
        Schema::dropIfExists('song');
    }
}