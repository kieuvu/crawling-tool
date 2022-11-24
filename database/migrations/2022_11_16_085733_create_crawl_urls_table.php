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
            $table->id();
            $table->unsignedBigInteger('site');
            $table->foreign('site')->references('id')->on('sites')->onDelete('cascade');
            $table->text('url');
            $table->string('url_hash')->index();
            $table->string('data_file')->nullable();
            $table->integer('visited')->default(-1)->index(); // INIT:-1, FAILED: -2, VISITING: 0 VISTIED: 1
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
