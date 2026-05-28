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
            $table->unsignedBigInteger('certifications_slot_type_id')->nullable();
            $table->foreign('certifications_slot_type_id')->references('id')->on('certifications_slot_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certifications_slot', function (Blueprint $table) {
            $table->dropForeign('certifications_slot_certification_slot_type_id_foreign');
            $table->dropColumn('certification_slot_type_id');
        });
    }
};
