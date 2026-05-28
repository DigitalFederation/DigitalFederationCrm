<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add the new status_class column
        Schema::table('insurances', function (Blueprint $table) {
            $table->string('status_class')->after('is_external')->nullable();
        });

        // Map existing status values to state classes
        DB::table('insurances')->where('status', 'active')->update([
            'status_class' => 'Domain\\Insurance\\States\\ActiveInsuranceState',
        ]);

        DB::table('insurances')->where('status', 'inactive')->update([
            'status_class' => 'Domain\\Insurance\\States\\PendingPaymentInsuranceState',
        ]);

        DB::table('insurances')->where('status', 'expired')->update([
            'status_class' => 'Domain\\Insurance\\States\\ExpiredInsuranceState',
        ]);

        DB::table('insurances')->where('status', 'suspended')->update([
            'status_class' => 'Domain\\Insurance\\States\\SuspendedInsuranceState',
        ]);

        // Set default for null values
        DB::table('insurances')->whereNull('status_class')->update([
            'status_class' => 'Domain\\Insurance\\States\\PendingPaymentInsuranceState',
        ]);

        // Make status_class not nullable
        Schema::table('insurances', function (Blueprint $table) {
            $table->string('status_class')->nullable(false)->change();
        });

        // Drop the old status column
        Schema::table('insurances', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the status column
        Schema::table('insurances', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'expired', 'suspended'])->default('active')->after('is_external');
        });

        // Map state classes back to status values
        DB::table('insurances')->where('status_class', 'Domain\\Insurance\\States\\ActiveInsuranceState')->update([
            'status' => 'active',
        ]);

        DB::table('insurances')->where('status_class', 'Domain\\Insurance\\States\\PendingPaymentInsuranceState')->update([
            'status' => 'inactive',
        ]);

        DB::table('insurances')->where('status_class', 'Domain\\Insurance\\States\\ExpiredInsuranceState')->update([
            'status' => 'expired',
        ]);

        DB::table('insurances')->where('status_class', 'Domain\\Insurance\\States\\SuspendedInsuranceState')->update([
            'status' => 'suspended',
        ]);

        // Drop the status_class column
        Schema::table('insurances', function (Blueprint $table) {
            $table->dropColumn('status_class');
        });
    }
};
