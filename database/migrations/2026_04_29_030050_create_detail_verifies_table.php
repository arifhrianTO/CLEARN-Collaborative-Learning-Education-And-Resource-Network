<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verifikasi mentor oleh admin (as admin -> verifies -> as mentor)
        Schema::create('detail_verifies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('mentor_id')->constrained('users')->cascadeOnDelete();
            $table->enum('action', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('mentor_rejection_reason')->nullable();
            $table->dateTime('verify_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_verifies');
    }
};
