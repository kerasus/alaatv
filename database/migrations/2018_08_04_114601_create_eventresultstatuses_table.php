<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEventresultstatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventresultstatuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')
                  ->nullable()
                  ->comment('نام این وضعیت');
            $table->string('displayName')
                  ->nullable()
                  ->comment('نام قابل نمایش این وضعیت');
            $table->text('description')
                  ->nullable()
                  ->comment('توضیح این وضعیت');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE `checkoutstatuses` comment 'وضعیت تسویه حساب'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eventresultstatuses');
    }
}
