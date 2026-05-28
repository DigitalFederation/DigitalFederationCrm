<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Modalidade (sport/activity) federations that should NOT be marked as local.
     * These are sport-specific associations, not territorial associations.
     *
     * - 147: Associação Nacional de Pesca Submarina e Tiro Subaquático
     * - 148: Centro Português de Actividades Subaquáticas
     * - 149: International diving federation
     *
     * Only territorial associations (regional) should have is_local = 1.
     * When an individual joins an entity, they should only be synced to local (territorial)
     * federations, not to modalidade federations.
     */
    private array $modalidadeFederationIds = [147, 148, 149];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('federation')
            ->whereIn('id', $this->modalidadeFederationIds)
            ->update(['is_local' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('federation')
            ->whereIn('id', $this->modalidadeFederationIds)
            ->update(['is_local' => 1]);
    }
};
