<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->unsignedBigInteger('cottage_id')->nullable();
            $table->foreign('cottage_id')->references('id')->on('cottages');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->dateTime('checkIn_at');
            $table->dateTime('checkOut_at')->nullable();
            $table->integer('adults')->default(0);
            $table->integer('kids')->default(0);
            $table->integer('senior')->default(0);
            $table->enum('type', ['day', 'night', 'overnight']);
            $table->boolean('is_breakfast')->default(false);
            $table->boolean('is_reservation')->default(false);
            $table->enum('status', ['pending', 'active', 'paid']);
            $table->text('notes')->nullable();
            $table->integer('extraPerson')->nullable();
            $table->decimal('extraPersonTotal', 10, 2)->nullable();
            $table->decimal('totalEntranceFee', 10, 2)->nullable();
            $table->decimal('breakfastfees', 10, 2)->nullable();
            $table->decimal('rentBill', 10, 2)->nullable();
            $table->decimal('totalBill', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
