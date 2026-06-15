<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNftLikesTable extends Migration
{
    public function up()
    {
        Schema::create('nft_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nft_id')->constrained('nfts')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'nft_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('nft_likes');
    }
}
