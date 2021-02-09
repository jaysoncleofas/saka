<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOvernightToEntrancefeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entrancefees', function (Blueprint $table) {
            $table->decimal('overnightPrice', 10, 2)->after('nightPrice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entrancefees', function (Blueprint $table) {
            $table->dropColumn('overnightPrice');
        });
    }
}
