<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotoShootsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_shoots', function (Blueprint $table) {
            $table->id();
            $table->string('hq_file_path');
            $table->string('thumbnail_file_path');
            $table->string('name');
            $table->boolean('status')->default(0);
            $table->unsignedBigInteger('photographer_request_id')->nullable();
            $table->foreign('photographer_request_id')->references('id')->on('photographer_requests')->onDelete('set null')->nullable();
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
        Schema::dropIfExists('photo_shoots');
    }
}
