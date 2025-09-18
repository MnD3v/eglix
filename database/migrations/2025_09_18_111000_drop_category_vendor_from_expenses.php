<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('expenses', 'vendor')) {
                $table->dropColumn('vendor');
            }
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'vendor')) {
                $table->string('vendor')->nullable();
            }
        });
    }
};


