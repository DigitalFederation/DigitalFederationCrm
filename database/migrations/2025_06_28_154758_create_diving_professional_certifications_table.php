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
        Schema::create('diving_professional_certifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('individual_id')->constrained('individual')->onDelete('cascade');
            $table->string('certification_name');
            $table->enum('certification_system', ['SSI', 'PADI', 'SDI_TDI', 'DDI', 'GUE', 'CMAS']);
            $table->string('certification_level');
            $table->string('certification_number');
            $table->string('national_equivalency')->nullable();
            $table->date('issue_date');
            $table->date('expiration_date')->nullable();
            $table->string('status_class');
            $table->text('validation_notes')->nullable();
            $table->foreignUuid('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();

            $table->index(['individual_id', 'certification_system'], 'diving_cert_individual_system_idx');
            $table->index('status_class', 'diving_cert_status_idx');
            $table->index('expiration_date', 'diving_cert_expiration_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diving_professional_certifications');
    }
};
