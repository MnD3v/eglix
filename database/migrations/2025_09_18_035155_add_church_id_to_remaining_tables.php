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
        // Ajouter church_id aux tables manquantes
        Schema::table('journal_images', function (Blueprint $table) {
            $table->foreignId('church_id')->nullable()->constrained()->onDelete('cascade');
        });

        Schema::table('administration_functions', function (Blueprint $table) {
            $table->foreignId('church_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_images', function (Blueprint $table) {
            $table->dropForeign(['church_id']);
            $table->dropColumn('church_id');
        });

        Schema::table('administration_functions', function (Blueprint $table) {
            $table->dropForeign(['church_id']);
            $table->dropColumn('church_id');
        });
    }
};
