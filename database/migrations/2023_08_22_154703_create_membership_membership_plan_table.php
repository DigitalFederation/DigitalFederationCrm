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
        Schema::create('membership_membership_plan', function (Blueprint $table) {
            $table->foreignId('membership_id')->constrained('membership')->cascadeOnDelete();
            $table->foreignId('membership_plan_id')->constrained('membership_plan')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_membership_plan');

        Schema::table('membership', function (Blueprint $table) {
            $table->foreignId('plan_id')->constrained('membership_plan');
        });
    }
};
