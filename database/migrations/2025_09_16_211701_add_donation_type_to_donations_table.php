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
        Schema::table('donations', function (Blueprint $table) {
            $table->enum('donation_type', ['money', 'physical'])->default('money')->after('amount');
            $table->string('physical_item')->nullable()->after('donation_type');
            $table->text('physical_description')->nullable()->after('physical_item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['donation_type', 'physical_item', 'physical_description']);
        });
    }
};
