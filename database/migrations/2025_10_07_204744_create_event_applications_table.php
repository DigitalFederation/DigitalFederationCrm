<?php

namespace Database\Migrations;

use App\Enums\EventApplicationTypeEnum;
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
    public $tableName = 'event_applications';

    /**
     * Run the migrations.
     *
     * @table event_applications
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->enum('application_type', EventApplicationTypeEnum::values());
            $table->foreignId('template_id')->nullable()->constrained('application_templates')->onDelete('set null');
            $table->unsignedBigInteger('entity_id'); // Polymorphic - no FK constraint
            $table->string('entity_type')->default('entity');
            $table->string('status_class', 200);
            $table->string('event_name');
            $table->enum('event_type', ['organization', 'competition']);
            $table->foreignId('sport_id')->nullable()->constrained('evt_sports')->onDelete('set null');
            $table->unsignedBigInteger('event_category_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('district_id')->nullable()->constrained('districts')->onDelete('set null');
            $table->string('municipality')->nullable();
            $table->string('responsible_name')->nullable();
            $table->string('responsible_phone')->nullable();
            $table->text('target_audience')->nullable();
            $table->integer('expected_participants')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes from Appendix B
            $table->index('status_class', 'idx_applications_status');
            $table->index(['entity_id', 'entity_type'], 'idx_applications_entity');
            $table->index('template_id', 'idx_applications_template');
            $table->index(['start_date', 'end_date'], 'idx_applications_dates');
            $table->index('submitted_at', 'idx_applications_submitted');

            // Compound index for faster duplicate checks (used by CheckDuplicateApplicationAction)
            $table->index(['template_id', 'entity_id', 'deleted_at'], 'idx_template_entity_deleted');
        });

        // NOTE: UNIQUE constraint for one-application-per-entity-per-template rule
        // MySQL doesn't support partial indexes with WHERE clauses (PostgreSQL feature)
        // Business rule enforced at application level via:
        // - CheckDuplicateApplicationAction (Domain/EventApplications/Actions)
        // - NoDuplicateApplication validation rule (app/Rules)
        // - Controller checks before form display
        // - Comprehensive tests in tests/Feature/EventApplications/BusinessRules/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
