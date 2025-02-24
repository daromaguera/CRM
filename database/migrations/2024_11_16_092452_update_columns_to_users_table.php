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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->text('firstname')->after('id')->nullable();
            $table->text('middlename')->after('firstname')->nullable();
            $table->text('lastname')->after('middlename')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('image')->nullable();
            $table->text('country')->nullable();
            $table->text('state_province')->nullable();
            $table->text('city_municipality')->nullable();
            $table->text('postal_zip')->nullable();
            $table->string('timezone', 25)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id')->nullable();
            $table->dropColumn('firstname');
            $table->dropColumn('middlename');
            $table->dropColumn('lastname');
            $table->dropColumn('phone');
            $table->dropColumn('phone_verified_at');
            $table->string('image')->nullable();
            $table->dropColumn('country');
            $table->dropColumn('state_province');
            $table->dropColumn('city_municipality');
            $table->dropColumn('postal_zip');
        });
    }
};
