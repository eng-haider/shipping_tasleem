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
        Schema::dropIfExists('model_histories');
        Schema::create('model_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('model_id');
            $table->string('model_type');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('user_type')->nullable();
            $table->string('message');
            $table->text('meta')->nullable();
            $table->timestamp('performed_at');
            $table->timestamps(); // Optionally, if you want to include created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
