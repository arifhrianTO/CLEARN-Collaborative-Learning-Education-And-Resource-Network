<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_project_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('final_project_id')->constrained('final_projects')->cascadeOnDelete();
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->integer('final_project_score')->nullable();
            $table->string('submission_file')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_project_results');
    }
};
