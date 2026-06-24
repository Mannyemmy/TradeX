<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The wdmethods table was created with only charges_fixed/charges_percentage,
 * but the app code (admin PaymentController, deposit/withdrawal flows) reads and
 * writes the richer payment-method fields below. Without these columns, adding a
 * payment method in admin fails with "Unknown column". This reconciles the table
 * with what the code expects.
 */
class AddMethodFieldsToWdmethodsTable extends Migration
{
    public function up()
    {
        Schema::table('wdmethods', function (Blueprint $table) {
            if (!Schema::hasColumn('wdmethods', 'charges_amount'))  $table->string('charges_amount')->nullable()->after('maximum');
            if (!Schema::hasColumn('wdmethods', 'charges_type'))    $table->string('charges_type')->nullable()->after('charges_amount');
            if (!Schema::hasColumn('wdmethods', 'img_url'))         $table->longText('img_url')->nullable();
            if (!Schema::hasColumn('wdmethods', 'bankname'))        $table->string('bankname')->nullable();
            if (!Schema::hasColumn('wdmethods', 'account_name'))    $table->string('account_name')->nullable();
            if (!Schema::hasColumn('wdmethods', 'account_number'))  $table->string('account_number')->nullable();
            if (!Schema::hasColumn('wdmethods', 'swift_code'))      $table->string('swift_code')->nullable();
            if (!Schema::hasColumn('wdmethods', 'wallet_address'))  $table->text('wallet_address')->nullable();
            if (!Schema::hasColumn('wdmethods', 'barcode'))         $table->string('barcode')->nullable();
            if (!Schema::hasColumn('wdmethods', 'network'))         $table->string('network')->nullable();
            if (!Schema::hasColumn('wdmethods', 'methodtype'))      $table->string('methodtype')->nullable();
            if (!Schema::hasColumn('wdmethods', 'defaultpay'))      $table->string('defaultpay', 20)->nullable();
        });
    }

    public function down()
    {
        Schema::table('wdmethods', function (Blueprint $table) {
            foreach ([
                'charges_amount', 'charges_type', 'img_url', 'bankname', 'account_name',
                'account_number', 'swift_code', 'wallet_address', 'barcode', 'network', 'methodtype',
            ] as $col) {
                if (Schema::hasColumn('wdmethods', $col)) $table->dropColumn($col);
            }
        });
    }
}
