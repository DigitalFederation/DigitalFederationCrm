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
    public $tableName = 'application_templates';

    /**
     * Run the migrations.
     *
     * @table application_templates
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('event_type', ['organization', 'competition']);
            $table->foreignId('sport_id')->nullable()->constrained('evt_sports')->onDelete('set null');
            $table->string('event_category')->nullable();
            $table->date('submission_start_date');
            $table->date('submission_end_date');
            $table->date('event_start_date')->nullable();
            $table->date('event_end_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('max_applications')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Indexes from Appendix B
            $table->index('is_active', 'idx_templates_active');
            $table->index(['submission_start_date', 'submission_end_date'], 'idx_templates_submission_dates');
            $table->index('event_type', 'idx_templates_event_type');
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
