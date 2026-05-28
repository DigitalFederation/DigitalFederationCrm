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
        Schema::table('evt_officials_enrollment', function (Blueprint $table) {
            // Add entity_id column
            $table->unsignedBigInteger('entity_id')->nullable()->after('federation_id');

            // Make federation_id nullable
            $table->unsignedBigInteger('federation_id')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_officials_enrollment', function (Blueprint $table) {
            //
        });
    }
};
