<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('categories');
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')
                  ->nullable();
            $table->string('description')
                  ->nullable();
            $table->boolean('enable')
                  ->default(1);
            $table->text('tags')
                  ->nullable();
            $table->timestamps();
            $table->nestedSet();
        });
        DB::statement("ALTER TABLE `categories` comment 'درخت دانش'");
        Artisan::call('cache:clear');
//        Artisan::call('alaaTv:seed:init:categorise');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
