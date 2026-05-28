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
        Schema::table('diving_log_validation', function (Blueprint $table) {
            // Remove old columns
            $table->dropColumn('individual_id');
            $table->dropColumn('validated_by_entity_id');

            // Add new columns for polymorphic relationship
            $table->string('validator_id', 36)->after('diving_log_id');
            $table->string('validator_type')->after('validator_id');

            // Add index for polymorphic relationship
            $table->index(['validator_id', 'validator_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diving_log_validation', function (Blueprint $table) {
            // Revert changes
            $table->dropIndex(['validator_id', 'validator_type']);
            $table->dropColumn('validator_type');
            $table->dropColumn('validator_id');

            $table->char('individual_id', 36)->after('diving_log_id');
            $table->unsignedBigInteger('validated_by_entity_id')->nullable()->after('validated_at');

            $table->index('individual_id');
        });
    }
};
