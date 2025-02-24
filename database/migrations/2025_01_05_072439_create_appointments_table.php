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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestampTz('date_start'); 
            $table->timestampTz('date_end');
            $table->string('timezone', 25); // DEFAULT user timezone
            $table->string('temporary_timezone', 25)->nullable(); // recorded if system detected new timezone when user transfer other places which having different timezone compared to the DEFAULT timezone
            $table->boolean('allDay');
            $table->string('comments')->nullable();
            $table->timestamps();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
         
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
