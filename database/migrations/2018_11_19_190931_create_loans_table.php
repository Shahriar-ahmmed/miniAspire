<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id');
            $table->enum('type',['personal','car','home','business'])->default('personal');
            $table->enum('repayments_frequency',['monthly','quarterly','half_yearly','yearly'])->default('monthly');
            $table->enum('status',['running','paid'])->default('running');
            $table->unsignedInteger('duration')->default(12);
            $table->unsignedInteger('interest_rate');
            $table->unsignedInteger('amount');
            $table->double('paid_amount',12,2)->nullable();
            $table->double('balance_amount',12,2)->nullable();
            $table->unsignedInteger('number_of_instalment');
            $table->double('instalment_amount',12,2);
            $table->unsignedInteger('arrangement_fee')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
