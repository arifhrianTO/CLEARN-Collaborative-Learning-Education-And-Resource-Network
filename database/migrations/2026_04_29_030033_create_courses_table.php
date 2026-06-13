<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('course_title');
            $table->string('course_slug')->unique();
            $table->text('course_description')->nullable();
            $table->string('course_thumbnail')->nullable();
            $table->decimal('course_price', 15, 2)->default(0.00);
            $table->enum('status_publish', ['draft', 'published'])->default('draft');
            $table->enum('status_review', ['draft','pending', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
