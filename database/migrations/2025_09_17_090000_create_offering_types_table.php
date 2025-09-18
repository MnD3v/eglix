<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offering_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->nullable()->index();
            $table->string('name');
            // Slug unique per church (or globally when church_id is null)
            $table->string('slug');
            $table->string('color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['church_id','slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offering_types');
    }
};


