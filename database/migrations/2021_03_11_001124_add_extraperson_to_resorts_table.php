<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtrapersonToResortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resorts', function (Blueprint $table) {
            $table->decimal('exclusive_day_extra', 10, 2)->default(200.00)->after('exclusive_dayprice')->nullable();
            $table->decimal('exclusive_overnight_extra', 10, 2)->default(250.00)->after('exclusive_dayprice')->nullable();
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
            $table->dropColumn('exclusive_day_extra');
            $table->dropColumn('exclusive_overnight_extra');
        });
    }
}
