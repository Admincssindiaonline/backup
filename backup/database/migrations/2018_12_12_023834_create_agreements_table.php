<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 16)->unique();
            $table->string('client_name', 64);
            $table->string('subject', 128);
            $table->string('initial_text', 1024);
            $table->string('notes', 1024)->nullable()->default(null);
            $table->timestamp('seen_at')->nullable()->default(null);
            $table->timestamp('accepted_at')->nullable()->default(null);

            $table->unsignedInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('users');

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
        Schema::dropIfExists('agreements');
    }
}
