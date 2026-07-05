<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('final_project_results', function (Blueprint $table) {
            $table->text('mentor_notes')->nullable()->after('final_project_score');
        });
    }

    public function down(): void
    {
        Schema::table('final_project_results', function (Blueprint $table) {
            $table->dropColumn('mentor_notes');
        });
    }
};
