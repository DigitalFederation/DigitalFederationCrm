<?php

use Domain\Individuals\Models\ProfessionalRole;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update "Visual Jury" to "Juiz de Audiovisuais"
        ProfessionalRole::where('code', 'VISUALJURY')->update([
            'name' => 'Juiz de Audiovisuais',
        ]);

        // Add "Treinador de Atividades Subaquaticas" (Underwater Sports Coach)
        // committee_id 1 = SPORT committee (same as other coaches)
        ProfessionalRole::create([
            'name' => 'Treinador de Atividades Subaquaticas',
            'code' => 'UNDERWATERSPORTSCOACH',
            'role' => 'COACH',
            'committee_id' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert "Juiz de Audiovisuais" back to "Visual Jury"
        ProfessionalRole::where('code', 'VISUALJURY')->update([
            'name' => 'Visual Jury',
        ]);

        // Remove the new coach role
        ProfessionalRole::where('code', 'UNDERWATERSPORTSCOACH')->delete();
    }
};
