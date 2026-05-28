<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Schema table name to migrate
     *
     * @var string
     */
    public $tableName = 'application_documents';

    /**
     * Run the migrations.
     *
     * @table application_documents
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->nullable()->constrained('event_applications')->onDelete('cascade');
            $table->foreignId('template_id')->nullable()->constrained('application_templates')->onDelete('cascade');
            $table->enum('document_type', ['template_guide', 'application_attachment']);
            $table->string('uploaded_by_type')->nullable();
            $table->unsignedBigInteger('uploaded_by_id')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size');
            $table->string('mime_type');
            $table->boolean('is_required')->default(false);
            $table->timestamps();

            $table->index(['application_id'], 'idx_application_documents_application');
            $table->index(['template_id'], 'idx_application_documents_template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
