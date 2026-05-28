<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add missing sport committee entity licenses for federation 142
        // Federation 142: Associação Territorial Norte de Actividades Subaquáticas

        $federation142Licenses = [
            // Entity licenses (type_id = 1)
            ['federation_id' => 142, 'license_id' => 4],  // Licença Anual Clube - Pesca Submarina
            ['federation_id' => 142, 'license_id' => 5],  // Licença Anual Clube - Hóquei Subaquático
            ['federation_id' => 142, 'license_id' => 6],  // Licença Anual Clube - Mergulho em Apneia
            ['federation_id' => 142, 'license_id' => 7],  // Licença Anual Clube - Natação com Barbatanas
            ['federation_id' => 142, 'license_id' => 8],  // Licença Anual Clube - Tiro Subaquático
            ['federation_id' => 142, 'license_id' => 9],  // Licença Anual Clube - Mergulho Desportivo

            // Individual licenses (type_id = 2) - trainers and judges
            ['federation_id' => 142, 'license_id' => 19], // Licença Treinador de Pesca Submarina
            ['federation_id' => 142, 'license_id' => 20], // Licença Treinador de Audiovisuais
            ['federation_id' => 142, 'license_id' => 21], // Licença Treinador de Hóquei Subaquático
            ['federation_id' => 142, 'license_id' => 22], // Licença Treinador de Natação com Barbatanas
            ['federation_id' => 142, 'license_id' => 23], // Licença Treinador de Mergulho em Apneia
            ['federation_id' => 142, 'license_id' => 24], // Licença Treinador de Tiro Subaquático
            ['federation_id' => 142, 'license_id' => 25], // Licença Treinador de Mergulho Desportivo
            ['federation_id' => 142, 'license_id' => 26], // Licença Treinador de Orientação Subaquática
            ['federation_id' => 142, 'license_id' => 27], // Licença de Treinador de Aquathlon
            ['federation_id' => 142, 'license_id' => 28], // Licença de Treinador de Râguebi Subaquático
            ['federation_id' => 142, 'license_id' => 29], // Licença Juíz de Pesca Submarina
            ['federation_id' => 142, 'license_id' => 30], // Licença Juíz de Mergulho em Apneia
            ['federation_id' => 142, 'license_id' => 31], // Licença de Árbitro de Hóquei Subaquático
        ];

        foreach ($federation142Licenses as $association) {
            // Check if the association already exists to avoid duplicates
            $exists = DB::table('federation_licenses')
                ->where('federation_id', $association['federation_id'])
                ->where('license_id', $association['license_id'])
                ->exists();

            if (! $exists) {
                // Check if the license exists and is active
                $licenseExists = DB::table('license')
                    ->where('id', $association['license_id'])
                    ->where('active', 1)
                    ->whereNull('deleted_at')
                    ->exists();

                if ($licenseExists) {
                    DB::table('federation_licenses')->insert([
                        'federation_id' => $association['federation_id'],
                        'license_id' => $association['license_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the associations added in this migration
        // Keep the original association (license_id = 3) that existed before
        DB::table('federation_licenses')
            ->where('federation_id', 142)
            ->whereIn('license_id', [4, 5, 6, 7, 8, 9, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31])
            ->delete();
    }
};
