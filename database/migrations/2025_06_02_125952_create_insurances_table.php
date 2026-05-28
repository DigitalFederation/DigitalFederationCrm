<?php

declare(strict_types=1);

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
        /*
        |----------------------------------------------------------------------
        | 1.  insurance_plans
        |----------------------------------------------------------------------
        */
        Schema::create('insurance_plans', function (Blueprint $table) {
            $table->id();

            // Core
            $table->string('name');
            $table->string('target_audience');
            $table->string('type');

            // Pricing
            $table->decimal('fee', 10, 2)->nullable();
            $table->decimal('individual_fee', 10, 2)->nullable();
            $table->decimal('entity_fee', 10, 2)->nullable();

            // Validity / Period
            $table->unsignedInteger('period')->nullable();          // e.g. 12
            $table->string('period_unit')->nullable();             // e.g. "months"
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Extra metadata
            $table->string('policy_number')->nullable();
            $table->string('insured_activity')->nullable();
            $table->string('territorial_scope')->nullable();
            $table->string('cmas_license_code')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
        });

        /*
        |----------------------------------------------------------------------
        | 2.  insurance_documents
        |----------------------------------------------------------------------
        */
        Schema::create('insurance_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('insurance_plan_id')
                ->constrained('insurance_plans')
                ->onDelete('cascade');

            $table->morphs('documentable');         // {documentable_type, documentable_id}
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->string('status_class');

            $table->timestamps();
        });

        /*
        |----------------------------------------------------------------------
        | 3.  insurances
        |----------------------------------------------------------------------
        */
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('insurance_plan_id')
                ->constrained()
                ->onDelete('cascade');

            $table->uuidMorphs('member');           // {member_type, member_id} (UUID)

            $table->foreignId('member_subscription_id')
                ->constrained()
                ->onDelete('cascade');

            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_external')->default(false);

            // Pricing overrides
            $table->decimal('individual_fee', 10, 2)->nullable();
            $table->decimal('entity_fee', 10, 2)->nullable();

            // Extra metadata
            $table->string('policy_number')->nullable();

            $table->timestamps();
        });

        /*
        |----------------------------------------------------------------------
        | 4.  package_insurance (pivot)
        |----------------------------------------------------------------------
        */
        Schema::create('package_insurance', function (Blueprint $table) {
            $table->foreignId('package_id')
                ->constrained('membership_packages')
                ->onDelete('cascade');

            $table->foreignId('insurance_id')
                ->constrained('insurance_plans')
                ->onDelete('cascade');

            $table->primary(['package_id', 'insurance_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop child tables first to satisfy FKs
        Schema::dropIfExists('package_insurance');
        Schema::dropIfExists('insurances');
        Schema::dropIfExists('insurance_documents');
        Schema::dropIfExists('insurance_plans');
    }
};
