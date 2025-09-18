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
        // Ajouter church_id à tous les modèles existants
        $tables = [
            'members',
            'tithes', 
            'offerings',
            'donations',
            'expenses',
            'projects',
            'services',
            'church_events',
            'service_roles',
            'service_assignments',
            // offering_types already includes church_id in its own migration
            'administration_function_types',
            'administration',
            'journal_entries'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableSchema) {
                    $tableSchema->foreignId('church_id')->nullable()->constrained()->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'members',
            'tithes', 
            'offerings',
            'donations',
            'expenses',
            'projects',
            'services',
            'church_events',
            'service_roles',
            'service_assignments',
            // offering_types handled in its own migration
            'administration_function_types',
            'administration',
            'journal_entries'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableSchema) {
                    $tableSchema->dropForeign(['church_id']);
                    $tableSchema->dropColumn('church_id');
                });
            }
        }
    }
};