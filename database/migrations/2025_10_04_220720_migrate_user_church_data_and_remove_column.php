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
        // Migrer les données existantes de church_id vers user_churches
        $users = DB::table('users')->whereNotNull('church_id')->get();
        
        foreach ($users as $user) {
            DB::table('user_churches')->insert([
                'user_id' => $user->id,
                'church_id' => $user->church_id,
                'is_primary' => true, // L'église existante devient l'église principale
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Supprimer la colonne church_id de manière sécurisée
        if (Schema::hasColumn('users', 'church_id')) {
            // Supprimer la contrainte de clé étrangère si elle existe
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['church_id']);
                });
            } catch (\Exception $e) {
                // La contrainte n'existe pas, continuer
                \Log::info('Contrainte users_church_id_foreign n\'existe pas, ignorée');
            }
            
            // Supprimer la colonne
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
        // Restaurer la colonne church_id
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('church_id')->nullable()->constrained()->onDelete('set null');
        });
        
        // Migrer les données de user_churches vers church_id
        $userChurches = DB::table('user_churches')->where('is_primary', true)->get();
        
        foreach ($userChurches as $userChurch) {
            DB::table('users')
                ->where('id', $userChurch->user_id)
                ->update(['church_id' => $userChurch->church_id]);
        }
    }
};
