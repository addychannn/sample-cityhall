<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alt')->nullable();
            $table->string('path');
            $table->string('mime');
            $table->string('extension');
            $table->tinyInteger('status')->default(1);
            $table->boolean('main')->default(false);
            $table->unsignedBigInteger('files_id')->nullable();
            $table->timestamps();

            $table->foreign('files_id')->references('id')->on('files')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forms',function(Blueprint $table){
            $table->dropForeign(['files_id']);
        });
        Schema::dropIfExists('forms');
    }
};
