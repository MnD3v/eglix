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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            // Add project_id without FK; FK will be added after projects table exists
            $table->foreignId('project_id')->nullable();
            $table->date('paid_at');
            $table->string('category'); // charge, salaire, social, maintenance, etc.
            $table->string('vendor')->nullable();
            $table->string('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->nullable();
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
