<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCenterTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_center_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_center_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->string('address');
            $table->string('slug');

            $table->unique(['service_center_id', 'locale']);
            $table->foreign('service_center_id')->references('id')->on('service_centers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_center_translations');
    }
}
