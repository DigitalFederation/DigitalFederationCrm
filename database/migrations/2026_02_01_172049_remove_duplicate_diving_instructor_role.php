<?php

use Domain\Individuals\Models\ProfessionalRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, update all certifications from duplicate (ID 12) to original (ID 8)
        DB::table('certification')
            ->where('professional_role_id', 12)
            ->update(['professional_role_id' => 8]);

        // Now remove the duplicate "Instrutor de Mergulho" (ID 12)
        ProfessionalRole::where('id', 12)->delete();
    }

    public function down(): void
    {
        // Recreate the duplicate role
        ProfessionalRole::create([
            'id' => 12,
            'code' => 'DIVINGINSTRUCTOR',
            'name' => 'Instrutor de Mergulho',
            'role' => 'INSTRUCTOR',
        ]);

        // Move technical diving certifications back to ID 12
        $certificationIds = [14, 19, 23, 25, 27, 29, 31, 33, 35, 36, 44, 58, 59, 65, 67, 152, 155, 159, 160, 161, 163];

        DB::table('certification')
            ->whereIn('id', $certificationIds)
            ->update(['professional_role_id' => 12]);
    }
};
