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
        // Vérifier si la colonne existe déjà
        if (!Schema::hasColumn('members', 'function')) {
            // Gérer les deadlocks avec retry
            $maxRetries = 3;
            $retryCount = 0;
            
            while ($retryCount < $maxRetries) {
                try {
                    Schema::table('members', function (Blueprint $table) {
                        $table->string('function')->nullable()->after('marital_status');
                    });
                    break; // Succès, sortir de la boucle
                } catch (\Exception $e) {
                    $retryCount++;
                    
                    if (strpos($e->getMessage(), 'Deadlock found') !== false && $retryCount < $maxRetries) {
                        // Attendre un peu avant de réessayer
                        sleep(rand(1, 3));
                        continue;
                    }
                    
                    // Si ce n'est pas un deadlock ou qu'on a épuisé les tentatives
                    throw $e;
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('function');
        });
    }
};
