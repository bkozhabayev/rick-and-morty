<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageIdColumnToCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // public function up()
    // {
    //     Schema::table('characters', function (Blueprint $table) {
    //         $table->unsignedBigInteger('image_id')->nullable();

    //         $table->foreign('image_id')->references('id')->on('images')->restrictOnDelete();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::table('characters', function (Blueprint $table) {
    //         $table->dropColumn('image_id');
    //     });
    // }
}
