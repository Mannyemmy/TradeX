<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTradingOverhaulColumnsToTradesTable extends Migration
{
    public function up()
    {
        Schema::table('trades', function (Blueprint $table) {
            $table->enum('trade_type', ['binary', 'spot'])->default('binary')->after('user_id');
            $table->boolean('is_demo')->default(false)->after('trade_type');
            $table->unsignedBigInteger('trading_asset_id')->nullable()->after('asset_name');
            $table->decimal('entry_price', 20, 8)->nullable()->after('amount');
            $table->decimal('exit_price', 20, 8)->nullable()->after('entry_price');
            $table->enum('settled_by', ['system', 'admin'])->nullable()->after('result');
            $table->timestamp('settled_at')->nullable()->after('settled_by');
            $table->timestamp('close_requested_at')->nullable()->after('settled_at');

            $table->foreign('trading_asset_id')->references('id')->on('trading_assets')->nullOnDelete();
            $table->index(['status', 'trade_type']);
            $table->index('is_demo');
        });

        // Tag all existing trades as binary, live
        DB::table('trades')->update([
            'trade_type' => 'binary',
            'is_demo' => false,
        ]);
    }

    public function down()
    {
        Schema::table('trades', function (Blueprint $table) {
            $table->dropForeign(['trading_asset_id']);
            $table->dropIndex(['status', 'trade_type']);
            $table->dropIndex(['is_demo']);
            $table->dropColumn([
                'trade_type', 'is_demo', 'trading_asset_id',
                'entry_price', 'exit_price',
                'settled_by', 'settled_at', 'close_requested_at',
            ]);
        });
    }
}
