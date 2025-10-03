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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            
            // Informations personnelles
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            
            // Informations visite
            $table->date('visit_date'); // Date de visite
            $table->enum('origin', ['referral', 'social_media', 'event', 'walk_in', 'flyer', 'other'])->default('walk_in');
            $table->string('referral_source')->nullable(); // Qui l'a amené ou autre source
            
            // Informations spirituelles optionnelles
            $table->string('church_background')->nullable(); // Ex: Église XYZ de Douala
            $table->string('spiritual_status')->nullable(); // Ex: Nouveau dans la foi, Chercheur, etc.
            $table->text('spiritual_notes')->nullable(); // Notes pastorales
            
            // Statut et suivi
            $table->enum('status', ['visit_1', 'visit_2_3', 'returning', 'member_converted', 'no_longer_interested'])->default('visit_1');
            $table->text('notes')->nullable(); // Notes générales
            
            // Relations avec l'église
            $table->foreignId('welcomed_by')->nullable()->constrained('users')->onDelete('set null'); // Qui l'a accueilli
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['church_id', 'visit_date']);
            $table->index(['church_id', 'status']);
            $table->index(['visit_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};