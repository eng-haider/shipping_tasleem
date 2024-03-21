<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('version')->nullable();
            $table->string('android_url')->nullable();
            $table->boolean('android_public')->default(0);
            $table->boolean('android_active')->default(0);
            $table->boolean('android_cache')->default(0);
            $table->string('ios_url')->nullable();
            $table->boolean('ios_public')->default(0);
            $table->boolean('ios_active')->default(0);
            $table->boolean('ios_cache')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('versions');
    }
};
