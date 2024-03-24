<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      
        Schema::dropIfExists('order_custumer');
      
        Schema::create('order_custumer', function (Blueprint $table) {
            $table->uuid('customer_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->boolean('is_active')->default(1);
            //add unique
            $table->unique(['customer_id', 'order_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_custumer');
    }
};
