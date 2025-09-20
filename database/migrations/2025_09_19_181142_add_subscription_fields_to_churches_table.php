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
        Schema::table('churches', function (Blueprint $table) {
            // Champs d'abonnement
            $table->date('subscription_start_date')->nullable()->after('updated_at');
            $table->date('subscription_end_date')->nullable()->after('subscription_start_date');
            $table->enum('subscription_status', ['active', 'expired', 'suspended'])->default('active')->after('subscription_end_date');
            $table->decimal('subscription_amount', 10, 2)->nullable()->after('subscription_status');
            $table->string('subscription_currency', 3)->default('XOF')->after('subscription_amount');
            $table->string('subscription_plan', 50)->default('basic')->after('subscription_currency');
            $table->text('subscription_notes')->nullable()->after('subscription_plan');
            $table->string('payment_reference')->nullable()->after('subscription_notes');
            $table->date('payment_date')->nullable()->after('payment_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('churches', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_start_date',
                'subscription_end_date', 
                'subscription_status',
                'subscription_amount',
                'subscription_currency',
                'subscription_plan',
                'subscription_notes',
                'payment_reference',
                'payment_date'
            ]);
        });
    }
};
