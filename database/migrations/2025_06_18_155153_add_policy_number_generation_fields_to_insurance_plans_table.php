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
            $table->string('policy_number_prefix')->nullable()->after('policy_number');
            $table->unsignedInteger('policy_number_sequence')->default(0)->after('policy_number_prefix');
            $table->string('policy_number_format')->nullable()->after('policy_number_sequence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurance_plans', function (Blueprint $table) {
            $table->dropColumn(['policy_number_prefix', 'policy_number_sequence', 'policy_number_format']);
        });
    }
};
