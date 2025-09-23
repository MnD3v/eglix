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
        // Vérifier si la table tithes existe
        if (Schema::hasTable('tithes')) {
            // Ajouter created_by seulement s'il n'existe pas
            if (!Schema::hasColumn('tithes', 'created_by')) {
                Schema::table('tithes', function (Blueprint $table) {
                    $table->foreignId('created_by')->nullable()->after('church_id');
                });
            }
            
            // Ajouter updated_by seulement s'il n'existe pas
            if (!Schema::hasColumn('tithes', 'updated_by')) {
                Schema::table('tithes', function (Blueprint $table) {
                    $table->foreignId('updated_by')->nullable()->after('created_by');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tithes', function (Blueprint $table) {
            $table->dropColumn(['created_by','updated_by']);
        });
    }
};
