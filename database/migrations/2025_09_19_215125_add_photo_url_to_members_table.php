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
        // Vérifier si la table members existe
        if (Schema::hasTable('members')) {
            // Ajouter photo_url seulement s'il n'existe pas
            if (!Schema::hasColumn('members', 'photo_url')) {
                // Déterminer la position de la colonne
                $afterColumn = 'updated_at'; // Position par défaut
                
                // Vérifier si la colonne photo existe
                if (Schema::hasColumn('members', 'photo')) {
                    $afterColumn = 'photo';
                } elseif (Schema::hasColumn('members', 'function')) {
                    $afterColumn = 'function';
                } elseif (Schema::hasColumn('members', 'marital_status')) {
                    $afterColumn = 'marital_status';
                }
                
                Schema::table('members', function (Blueprint $table) use ($afterColumn) {
                    $table->string('photo_url')->nullable()->after($afterColumn);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('photo_url');
        });
    }
};
