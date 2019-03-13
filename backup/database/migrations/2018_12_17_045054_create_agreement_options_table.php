<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgreementOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreement_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('prompt');
            $table->boolean('value')->nullable()->default(null);

            $table->unsignedInteger('agreement_id');
            $table->foreign('agreement_id')->references('id')->on('agreements');

            $table->timestamps();
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
        Schema::dropIfExists('agreement_options');
    }
}
