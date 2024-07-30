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
        Schema::create('quitters', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->dateTime('preferred_date');
            $table->string('location'); //House no. and Street
            $table->string('barangay_code', 10); //quitter barangay
            $table->longText('additional_notes')->nullable();
            $table->tinyInteger('status')->default(0); //0=pending 1=approved 2=rejected 3=rescheduled
            $table->longText('admin_remarks')->nullable();
            $table->tinyInteger('reschedule_count')->default(0);
            $table->softDeletes(); //for rejected quitters appointment
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quitters');
    }
};
