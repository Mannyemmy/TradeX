<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockCatalogQueryIndexes extends Migration
{
    public function up()
    {
        Schema::table('trading_assets', function (Blueprint $table) {
            $table->index(['asset_class', 'symbol', 'data_source'], 'trading_assets_stock_catalog_idx');
            $table->index(['asset_class', 'is_active', 'symbol'], 'trading_assets_active_symbols_idx');
        });
    }

    public function down()
    {
        Schema::table('trading_assets', function (Blueprint $table) {
            $table->dropIndex('trading_assets_stock_catalog_idx');
            $table->dropIndex('trading_assets_active_symbols_idx');
        });
    }
}
