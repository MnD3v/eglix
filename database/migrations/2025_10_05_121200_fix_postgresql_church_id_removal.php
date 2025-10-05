<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vérifier si la colonne church_id existe
        if (Schema::hasColumn('users', 'church_id')) {
            // Supprimer la contrainte de clé étrangère si elle existe
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['church_id']);
                });
            } catch (Exception $e) {
                // La contrainte n'existe pas, continuer
                \Log::info('Contrainte users_church_id_foreign n\'existe pas, ignorée');
            }
            
            // Supprimer la colonne church_id
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('church_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ajouter la colonne church_id
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('church_id')->nullable()->constrained()->onDelete('set null');
        });
        
        // Restaurer les données depuis user_churches
        $userChurches = DB::table('user_churches')->where('is_primary', true)->get();
        foreach ($userChurches as $userChurch) {
            DB::table('users')
                ->where('id', $userChurch->user_id)
                ->update(['church_id' => $userChurch->church_id]);
        }
    }
};
