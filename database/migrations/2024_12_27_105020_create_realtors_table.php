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
        Schema::create('realtors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->text('name')->nullable();
            $table->text('profile_img')->nullable();
            $table->text('company_name');
            $table->text('license_number');
            $table->text('twitter')->nullable();
            $table->text('instagram')->nullable();
            $table->text('youtube')->nullable();
            $table->text('facebook_etc')->nullable();
            $table->text('biography')->nullable();
            $table->text('pitch_video_url')->nullable();
            $table->text('qr_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realtors');
    }
};
