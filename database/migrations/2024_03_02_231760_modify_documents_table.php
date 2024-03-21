
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
        Schema::table('documents', function (Blueprint $table) {
            
            $table->string('front_image')->nullable()->default(null)->after('name'); // Add new column 'back_image' with default as null
            $table->string('back_image')->nullable()->default(null)->after('front_image'); // Add new column 'back_image' with default as null
            $table->tinyInteger('image_number')->nullable()->default(1)->after('back_image'); // Add new column 'image_number' of type tinyInteger with default 1
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->renameColumn('front_image', 'image'); // Revert the name of 'front_image' column back to 'image'
            $table->dropColumn('back_image'); // Drop the 'back_image' column
            $table->dropColumn('image_number'); // Drop the 'image_number' column
        });
    }
};
