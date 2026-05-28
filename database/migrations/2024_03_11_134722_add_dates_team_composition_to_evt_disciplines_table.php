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
        Schema::table('evt_disciplines', function (Blueprint $table) {
            // Adding interval date of birth fields
            $table->date('interval_date_birth_start')->nullable();
            $table->date('interval_date_birth_end')->nullable();

            // Adding JSON field for detailed team composition requirements
            // This will allow for specifying things like "2 men and 2 women" for a mixed relay
            $table->json('team_composition_requirements')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_disciplines', function (Blueprint $table) {
            $table->dropColumn('interval_date_birth_start');
            $table->dropColumn('interval_date_birth_end');
            $table->dropColumn('team_composition_requirements');
        });
    }
};
