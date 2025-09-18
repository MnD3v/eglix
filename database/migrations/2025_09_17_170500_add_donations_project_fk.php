<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (! Schema::hasColumn('donations', 'project_id')) {
                $table->foreignId('project_id')->nullable()->after('member_id');
            }
            // Guard against missing projects table at runtime
            if (Schema::hasTable('projects')) {
                $table->foreign('project_id')->references('id')->on('projects')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (Schema::hasColumn('donations', 'project_id')) {
                $table->dropForeign(['project_id']);
            }
        });
    }
};


