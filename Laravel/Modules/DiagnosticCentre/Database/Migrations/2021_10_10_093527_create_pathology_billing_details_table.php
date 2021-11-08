<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathologyBillingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pathology_billing_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pathology_billing_id');
            $table->foreign('pathology_billing_id')->references('id')->on('pathology_billings')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->double('price', 15, 2);
            $table->double('quantity', 15, 2)->default(1);
            $table->double('discount_percentage', 15, 2)->nullable();
            $table->double('discount_amount', 15, 2)->nullable();
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
        Schema::dropIfExists('pathology_billing_details');
    }
}
