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
            $table->string('controlCode');
            $table->unsignedBigInteger('guest_id');
            $table->foreign('guest_id')->references('id')->on('guests');
            $table->unsignedBigInteger('cottage_id')->nullable();
            $table->foreign('cottage_id')->references('id')->on('cottages');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->unsignedBigInteger('receivedby_id')->nullable();
            $table->foreign('receivedby_id')->references('id')->on('users');
            $table->boolean('is_exclusive')->default(false);
            $table->dateTime('checkIn_at');
            $table->dateTime('checkOut_at')->nullable();
            $table->integer('adults')->default(0);
            $table->integer('kids')->default(0);
            $table->integer('senior')->default(0);
            $table->enum('type', ['day', 'night', 'overnight']);
            $table->boolean('is_breakfast')->default(false);
            $table->boolean('is_freebreakfast')->default(false);
            $table->boolean('is_reservation')->default(false);
            $table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled']);
            $table->text('notes')->nullable();
            $table->integer('extraPerson')->nullable();
            $table->decimal('extraPersonTotal', 10, 2)->nullable();
            $table->decimal('totalEntranceFee', 10, 2)->nullable();
            $table->decimal('breakfastfees', 10, 2)->nullable();
            $table->decimal('rentBill', 10, 2)->nullable();
            $table->decimal('totalBill', 10, 2)->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
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
