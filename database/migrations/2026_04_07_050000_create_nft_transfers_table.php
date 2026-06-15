<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNftTransfersTable extends Migration
{
    public function up()
    {
        Schema::create('nft_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nft_id')->constrained('nfts')->cascadeOnDelete();
            $table->foreignId('from_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('price', 18, 8)->default(0);
            $table->string('type'); // mint, sale, bid_accept, transfer
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nft_transfers');
    }
}
