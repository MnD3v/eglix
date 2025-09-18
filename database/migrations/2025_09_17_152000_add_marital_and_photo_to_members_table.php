<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('marital_status')->nullable()->after('gender'); // single, married, divorced, widowed
            $table->string('profile_photo')->nullable()->after('marital_status');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['marital_status','profile_photo']);
        });
    }
};


