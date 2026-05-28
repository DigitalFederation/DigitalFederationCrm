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
        Schema::create('evt_individual_enrollment_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('individual_enrollment_id');
            $table->unsignedBigInteger('attribute_id');
            $table->text('value')->nullable();

            // Add foreign keys with shorter names
            $table->foreign('individual_enrollment_id', 'ind_enroll_attr_ind_enroll_id_foreign')
                ->references('id')
                ->on('evt_individuals_enrollment')
                ->onDelete('cascade');
            // Add foreign keys with shorter names
            $table->foreign('attribute_id', 'ind_enroll_attr_attr_id_foreign')
                ->references('id')
                ->on('evt_attributes')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_individual_enrollment_attributes');
    }
};
