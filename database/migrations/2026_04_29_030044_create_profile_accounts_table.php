<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('expertise')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->string('sinta_link')->nullable();
            $table->string('scopus_link')->nullable();
            $table->string('front_title')->nullable();
            $table->string('back_title')->nullable();
            $table->string('cv_file')->nullable();
            $table->string('certificate_file')->nullable();
            $table->string('diploma_file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_accounts');
    }
};
