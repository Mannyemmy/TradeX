<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopyTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // No-op: this was a duplicate of the experts table creation. Both the
        // experts table (2025_03_07_162602) and the copy_trades table
        // (2025_03_07_163612) are now created by their correct migrations.
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copy_trades');
    }
}
