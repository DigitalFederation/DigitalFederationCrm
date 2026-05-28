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
        Schema::table('certification', function (Blueprint $table) {
            // Availability flag
            $table->boolean('is_available')->default(true)->after('theoretical_sessions');

            // Pricing fields
            $table->decimal('unit_value', 10, 2)->nullable()->after('is_available');
            $table->decimal('unit_value_individual', 10, 2)->nullable()->after('unit_value');
            $table->decimal('unit_value_entity', 10, 2)->nullable()->after('unit_value_individual');
            $table->decimal('tax_value', 10, 2)->nullable()->after('unit_value_entity');
            $table->decimal('tax_percentage', 5, 2)->nullable()->after('tax_value');

            // Purchase configuration
            $table->string('requester_model')->default('all')->after('tax_percentage'); // 'Individual', 'Entity', 'all'
            $table->boolean('allow_entity_group_request')->default(false)->after('requester_model');
            $table->boolean('requires_admin_validation')->default(false)->after('allow_entity_group_request');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification', function (Blueprint $table) {
            $table->dropColumn([
                'is_available',
                'unit_value',
                'unit_value_individual',
                'unit_value_entity',
                'tax_value',
                'tax_percentage',
                'requester_model',
                'allow_entity_group_request',
                'requires_admin_validation',
            ]);
        });
    }
};
