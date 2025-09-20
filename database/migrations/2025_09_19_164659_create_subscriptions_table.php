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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            
            // Informations sur l'abonnement
            $table->string('plan_name')->default('basic'); // basic, premium, enterprise
            $table->decimal('amount', 10, 2); // Montant de l'abonnement
            $table->string('currency', 3)->default('XOF'); // Devise (XOF pour le franc CFA)
            $table->integer('max_members')->default(100); // Limite de membres
            $table->boolean('has_advanced_reports')->default(false);
            $table->boolean('has_api_access')->default(false);
            
            // Dates importantes
            $table->date('start_date'); // Date de début de l'abonnement
            $table->date('end_date'); // Date de fin de l'abonnement
            $table->date('payment_date')->nullable(); // Date du paiement physique
            
            // Statut et gestion
            $table->enum('is_active', ['active', 'expired', 'suspended'])->default('active');
            $table->enum('payment_status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_money', 'check'])->default('cash');
            
            // Informations supplémentaires
            $table->text('notes')->nullable(); // Notes de l'admin
            $table->string('receipt_number')->nullable(); // Numéro de reçu
            $table->string('payment_reference')->nullable(); // Référence de paiement
            
            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['church_id', 'is_active']);
            $table->index(['end_date', 'is_active']);
            $table->index(['payment_status', 'payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
