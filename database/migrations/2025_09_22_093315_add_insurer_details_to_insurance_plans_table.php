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
        Schema::table('insurance_plans', function (Blueprint $table) {
            // Add insurer contact information
            $table->text('insurer_address')->nullable()->after('territorial_scope');
            $table->string('insurer_email')->nullable()->after('insurer_address');
            $table->string('insurer_phone', 50)->nullable()->after('insurer_email');

            // Add coverage details
            $table->text('applicable_deductibles')->nullable()->after('insurer_phone');
            $table->text('coverage_details')->nullable()->after('applicable_deductibles');
        });

        // Modify existing fields to support longer content
        Schema::table('insurance_plans', function (Blueprint $table) {
            $table->text('insured_activity')->nullable()->change();
            $table->text('territorial_scope')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurance_plans', function (Blueprint $table) {
            // Drop new fields
            $table->dropColumn([
                'insurer_address',
                'insurer_email',
                'insurer_phone',
                'applicable_deductibles',
                'coverage_details',
            ]);
        });

        // Revert field types
        Schema::table('insurance_plans', function (Blueprint $table) {
            $table->string('insured_activity')->nullable()->change();
            $table->string('territorial_scope')->nullable()->change();
        });
    }
};
