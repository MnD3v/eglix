<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier si la table members existe
        if (Schema::hasTable('members')) {
            // Ajouter marital_status seulement s'il n'existe pas
            if (!Schema::hasColumn('members', 'marital_status')) {
                Schema::table('members', function (Blueprint $table) {
                    $table->string('marital_status')->nullable()->after('gender'); // single, married, divorced, widowed
                });
            }
            
            // Ajouter profile_photo seulement s'il n'existe pas
            if (!Schema::hasColumn('members', 'profile_photo')) {
                Schema::table('members', function (Blueprint $table) {
                    $table->string('profile_photo')->nullable()->after('marital_status');
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['marital_status','profile_photo']);
        });
    }
};


