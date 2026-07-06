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
        Schema::table('final_project_results', function (Blueprint $table) {
            $table->dateTime('started_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('final_project_results', function (Blueprint $table) {
            $table->dropColumn('started_at');
        });
    }
};
