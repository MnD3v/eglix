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
        // Pour SQLite, nous devons recréer la table sans la colonne church_id
        if (DB::getDriverName() === 'sqlite') {
            // Créer une nouvelle table users_temp sans church_id
            DB::statement('
                CREATE TABLE users_temp (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    email_verified_at DATETIME NULL,
                    password VARCHAR(255) NOT NULL,
                    remember_token VARCHAR(100) NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL,
                    role_id INTEGER NULL,
                    is_church_admin BOOLEAN DEFAULT 0,
                    is_active BOOLEAN DEFAULT 1,
                    is_super_admin BOOLEAN DEFAULT 0,
                    church_name VARCHAR(255) NULL
                )
            ');

            // Copier les données de users vers users_temp (sans church_id)
            DB::statement('
                INSERT INTO users_temp (
                    id, name, email, email_verified_at, password, remember_token,
                    created_at, updated_at, role_id, is_church_admin, is_active,
                    is_super_admin, church_name
                )
                SELECT 
                    id, name, email, email_verified_at, password, remember_token,
                    created_at, updated_at, role_id, is_church_admin, is_active,
                    is_super_admin, church_name
                FROM users
            ');

            // Supprimer l'ancienne table users
            DB::statement('DROP TABLE users');

            // Renommer users_temp en users
            DB::statement('ALTER TABLE users_temp RENAME TO users');
        } else {
            // Pour MySQL/PostgreSQL, utiliser la méthode standard
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['church_id']);
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
    }
};