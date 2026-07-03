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
        Schema::table('final_projects', function (Blueprint $table) {
            if (Schema::hasColumn('final_projects', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('final_projects', 'due_date')) {
                $table->dropColumn('due_date');
            }
            if (!Schema::hasColumn('final_projects', 'duration_days')) {
                $table->integer('duration_days')->nullable()->after('project_description');
            }
            if (!Schema::hasColumn('final_projects', 'allowed_extensions')) {
                $table->string('allowed_extensions')->nullable()->after('duration_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('final_projects', function (Blueprint $table) {
            $table->dropColumn(['duration_days', 'allowed_extensions']);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date')->nullable();
        });
    }
};
