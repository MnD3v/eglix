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
        if (Schema::hasTable('members')) {
            if (!Schema::hasColumn('members', 'baptism_responsible')) {
                Schema::table('members', function (Blueprint $table) {
                    $table->string('baptism_responsible')->nullable()->after('baptism_date');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('members')) {
            if (Schema::hasColumn('members', 'baptism_responsible')) {
                Schema::table('members', function (Blueprint $table) {
                    $table->dropColumn('baptism_responsible');
                });
            }
        }
    }
};
