<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathologyBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pathology_billings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('referrer_id');
            $table->foreign('referrer_id')->references('id')->on('doctors')->onDelete('cascade')->onUpdate('cascade');
            $table->date('bill_date');
            $table->date('delivery_date');
            $table->time('delivery_time');
            $table->string('remarks');
            $table->double('sub_total', 15, 2);
            $table->double('tax', 15, 2)->nullable();
            $table->double('discount', 15, 2)->nullable();
            $table->double('total', 15, 2);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('pathology_billings');
    }
}
