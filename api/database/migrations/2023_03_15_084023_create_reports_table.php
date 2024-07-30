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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->string('violator_name')->nullable();
            $table->string('violator_location'); //bldg no. and street
            $table->string('barangay_code', 10); //violator barangay
            $table->date('violation_date');
            $table->longText('landmark');
            $table->longText('violation_description');
            $table->longText('admin_remarks')->nullable();
            $table->tinyInteger('status')->default(0); //0=pending 1=resolved 2=rejected
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
