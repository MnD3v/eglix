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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom affiché
            $table->string('original_name'); // Nom original du fichier
            $table->string('file_path'); // Chemin dans Firebase Storage
            $table->text('file_url'); // URL publique du fichier
            $table->string('file_type'); // Type de fichier (image, pdf, etc.)
            $table->bigInteger('file_size'); // Taille en bytes
            $table->string('mime_type'); // Type MIME
            $table->text('description')->nullable();
            $table->foreignId('folder_id')->constrained('document_folders')->onDelete('cascade');
            $table->boolean('is_public')->default(false);
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['folder_id', 'is_public']);
            $table->index(['church_id', 'file_type']);
            $table->index(['church_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
