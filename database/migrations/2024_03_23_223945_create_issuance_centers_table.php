<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issuance_centers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            // $table->uuid('governorate_id')->nullable();
            $table->string('address')->nullable();
            $table->string('governorate');
            // $table->unsignedBigInteger('creator_id')->nullable();
            // $table->unsignedBigInteger('updator_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
            $table->softDeletes();
            //foreign
            // $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('cascade');
            // $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('updator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issuance_centers');
    }
};
