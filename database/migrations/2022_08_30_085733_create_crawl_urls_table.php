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
        Schema::create('crawl_urls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('site')->index();
            $table->text('url');
            $table->string('url_hash')->index();
            $table->json('data')->nullable();
            $table->integer('visited')->default(-1)->index(); // INIT:-1, VISITING: 0, VISTIED: 1
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crawl_urls');
    }
};
