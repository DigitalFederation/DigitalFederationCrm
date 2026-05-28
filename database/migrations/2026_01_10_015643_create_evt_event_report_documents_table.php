<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evt_event_report_documents', function (Blueprint $table) {
            $table->id();
            $table->string('documentable_type');
            $table->unsignedBigInteger('documentable_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size');
            $table->string('mime_type');
            $table->foreignUuid('uploaded_by')->constrained('individual')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['documentable_type', 'documentable_id'], 'evt_report_docs_morph_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evt_event_report_documents');
    }
};
