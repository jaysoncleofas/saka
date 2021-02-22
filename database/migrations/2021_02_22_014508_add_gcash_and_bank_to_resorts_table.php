<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGcashAndBankToResortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resorts', function (Blueprint $table) {
            $table->string('gcash_account')->after('sms_on')->nullable();
            $table->string('gcash_number')->after('sms_on')->nullable();
            $table->string('bank_account')->after('sms_on')->nullable();
            $table->string('bank')->after('sms_on')->nullable();
            $table->string('bank_accountnumber')->after('sms_on')->nullable();
            $table->decimal('exclusive_dayprice', 10, 2)->default(15000.00)->after('sms_on')->nullable();
            $table->decimal('exclusive_overnightprice', 10, 2)->default(25000.00)->after('sms_on')->nullable();
            $table->integer('exclusive_daycapacity')->default(60)->after('sms_on')->nullable();
            $table->integer('exclusive_overnightcapacity')->default(30)->after('sms_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resorts', function (Blueprint $table) {
            $table->dropColumn('gcash_account');
            $table->dropColumn('gcash_number');
            $table->dropColumn('bank_account');
            $table->dropColumn('bank');
            $table->dropColumn('bank_accountnumber');
            $table->dropColumn('exclusive_dayprice');
            $table->dropColumn('exclusive_overnightprice');
            $table->dropColumn('exclusive_daycapacity');
            $table->dropColumn('exclusive_overnightcapacity');
        });
    }
}
