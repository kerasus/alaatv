<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableContentsetsAddColumnSmallName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contentsets', function (Blueprint $table) {
            $table->string('small_name')
                  ->after('name')
                  ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contentsets', function (Blueprint $table) {
            $table->dropColumn('small_name');
        });
    }
}
