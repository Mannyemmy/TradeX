<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OverhaulNftsTable extends Migration
{
    public function up()
    {
        Schema::table('nfts', function (Blueprint $table) {
            $table->foreignId('collection_id')->nullable()->after('user_id')->constrained('nft_collections')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->after('collection_id')->constrained('nft_categories')->nullOnDelete();
            $table->string('token_id', 64)->nullable()->unique()->after('id');
            $table->string('blockchain')->default('Ethereum')->after('category');
            $table->decimal('royalty_percent', 5, 2)->default(0)->after('price');
            $table->json('properties')->nullable()->after('image');
            $table->unsignedInteger('views_count')->default(0)->after('status');
            $table->unsignedInteger('likes_count')->default(0)->after('views_count');
            $table->boolean('is_featured')->default(false)->after('likes_count');
            $table->boolean('is_approved')->default(false)->after('is_featured');
            $table->foreignId('original_creator_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->timestamp('minted_at')->nullable()->after('updated_at');
        });

        // Backfill existing NFTs: set original_creator_id = user_id, mark approved
        \DB::statement('UPDATE nfts SET original_creator_id = user_id, is_approved = 1, token_id = CONCAT("TXP-", id) WHERE token_id IS NULL');
    }

    public function down()
    {
        Schema::table('nfts', function (Blueprint $table) {
            $table->dropForeign(['collection_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['original_creator_id']);
            $table->dropColumn([
                'collection_id', 'category_id', 'token_id', 'blockchain',
                'royalty_percent', 'properties', 'views_count', 'likes_count',
                'is_featured', 'is_approved', 'original_creator_id', 'minted_at',
            ]);
        });
    }
}
