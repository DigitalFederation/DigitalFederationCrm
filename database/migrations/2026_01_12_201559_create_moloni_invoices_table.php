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
        Schema::create('moloni_invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('document_id', 36);
            $table->unsignedInteger('moloni_document_id');
            $table->string('moloni_document_set_id', 50)->nullable();
            $table->string('moloni_number', 50);
            $table->string('moloni_status', 50);
            $table->decimal('moloni_total', 10, 2);
            $table->json('moloni_response')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->foreign('document_id')
                ->references('id')
                ->on('document')
                ->onDelete('cascade');

            $table->unique('document_id');
            $table->index('moloni_document_id');
            $table->index('moloni_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moloni_invoices');
    }
};
