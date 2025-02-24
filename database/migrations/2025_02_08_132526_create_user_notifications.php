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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->bigIncrements('id'); // Make the id column nullable
            $table->timestamps(); // Fix the timestamps method call
            $table->string('title')->nullable();
            $table->string('message')->nullable();
            $table->string('subtitle')->nullable();
            $table->boolean('isSeen')->default(false);
            $table->string('img')->nullable();

            // Add user_id column and set up foreign key relationship with users table
            $table->unsignedBigInteger('user_id')->nullable(); // Make user_id nullable for public notifications
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
