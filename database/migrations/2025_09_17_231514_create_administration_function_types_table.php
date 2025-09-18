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
        Schema::create('administration_function_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de la fonction
            $table->string('slug')->unique(); // Slug unique
            $table->text('description')->nullable(); // Description de la fonction
            $table->boolean('is_active')->default(true); // Fonction active ou non
            $table->integer('sort_order')->default(0); // Ordre d'affichage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administration_function_types');
    }
};