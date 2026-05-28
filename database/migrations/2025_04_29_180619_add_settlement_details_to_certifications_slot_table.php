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
        Schema::table('certifications_slot', function (Blueprint $table) {
            // Add settlement_type column
            $table->string('settlement_type')->nullable()->after('status_class')->index()->comment('Indicates how the slot was settled (online_payment, offline_payment, stock_exchange, manual_approval)');
            // Add settlement_notes column
            $table->text('settlement_notes')->nullable()->after('settlement_type')->comment('Optional notes regarding manual settlement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certifications_slot', function (Blueprint $table) {
            // Use try-catch for robustness if column might not exist
            try {
                $table->dropIndex(['settlement_type']); // Drop index first if it exists
            } catch (\Exception $e) {
                // Log or ignore if index doesn't exist
            }
            $table->dropColumn(['settlement_type', 'settlement_notes']);
        });
    }
};
