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
        Schema::table('entity_professional_role_invitations', function (Blueprint $table) {
            if (! Schema::hasColumn('entity_professional_role_invitations', 'sport_id')) {
                $table->foreignId('sport_id')
                    ->nullable()
                    ->after('professional_role_id')
                    ->constrained('sports')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entity_professional_role_invitations', function (Blueprint $table) {
            if (Schema::hasColumn('entity_professional_role_invitations', 'sport_id')) {
                $table->dropConstrainedForeignId('sport_id');
            }
        });
    }
};
