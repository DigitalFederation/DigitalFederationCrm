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
        Schema::table('entity_professional_role', function (Blueprint $table) {
            if (! Schema::hasColumn('entity_professional_role', 'sport_id')) {
                $table->foreignId('sport_id')->nullable()->after('professional_role_id')->constrained('sports')->onDelete('cascade');
            }

            if (! Schema::hasIndex('entity_professional_role', 'entity_professional_role_entity_id_individual_id_sport_id_index')) {
                $table->index(['entity_id', 'individual_id', 'sport_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entity_professional_role', function (Blueprint $table) {
            $table->dropForeign(['sport_id']);
            $table->dropIndex(['entity_id', 'individual_id', 'sport_id']);
            $table->dropColumn('sport_id');
        });
    }
};
