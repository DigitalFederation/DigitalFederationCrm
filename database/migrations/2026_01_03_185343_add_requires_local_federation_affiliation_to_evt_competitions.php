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
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->boolean('requires_local_federation_affiliation')->default(false)
                ->after('requires_official_adel')
                ->comment('Requires athlete to have active membership in the same local federation as the registering entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->dropColumn('requires_local_federation_affiliation');
        });
    }
};
