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
        Schema::create('local_membership_plan_associations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('local_federation_id');
            $table->unsignedBigInteger('membership_plan_id');
            $table->timestamps();

            $table->foreign('local_federation_id')->references('id')->on('federation');
            $table->foreign('membership_plan_id')->references('id')->on('membership_plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_membership_plan_associations');
    }
};
