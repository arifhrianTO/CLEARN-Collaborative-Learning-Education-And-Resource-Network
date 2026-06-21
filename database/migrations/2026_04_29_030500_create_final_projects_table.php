<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sessions_id')->constrained('sessions')->cascadeOnDelete();
            $table->string('project_title')->nullable();
            $table->text('project_description')->nullable();
            $table->integer('duration_days')->nullable();
            $table->string('allowed_extensions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_projects');
    }
};
