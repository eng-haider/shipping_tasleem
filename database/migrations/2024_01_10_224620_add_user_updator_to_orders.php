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
        // Remove the column
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['updator_id']);
            $table->dropColumn('updator_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('driver_updator_id')->after('status_id')->nullable();
            $table->foreign('driver_updator_id')->references('id')->on('drivers')->onDelete('cascade');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
