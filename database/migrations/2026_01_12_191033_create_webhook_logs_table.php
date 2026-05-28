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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('gateway', 50)->index();
            $table->string('request_id', 100)->nullable()->index();
            $table->string('status', 50)->index();
            $table->string('ip_address', 45)->nullable();
            $table->json('headers')->nullable();
            $table->json('payload')->nullable();
            $table->json('response')->nullable();
            $table->char('transaction_id', 36)->nullable()->index();
            $table->char('document_id', 36)->nullable()->index();
            $table->text('error_message')->nullable();
            $table->integer('response_code')->nullable();
            $table->integer('processing_time_ms')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('payment_transactions')
                ->onDelete('set null');

            $table->foreign('document_id')
                ->references('id')
                ->on('document')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
