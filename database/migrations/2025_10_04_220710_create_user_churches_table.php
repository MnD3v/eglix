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
        Schema::create('user_churches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            $table->boolean('is_primary')->default(false); // Église principale de l'utilisateur
            $table->boolean('is_active')->default(true); // Si l'utilisateur a accès à cette église
            $table->timestamps();
            
            // Index unique pour éviter les doublons
            $table->unique(['user_id', 'church_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_churches');
    }
};
