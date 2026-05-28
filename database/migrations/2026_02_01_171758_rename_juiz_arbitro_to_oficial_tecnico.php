<?php

use Domain\Individuals\Models\ProfessionalRole;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $updates = [
            'AQUATHLONREFEREE' => 'Oficial Técnico de Aquatlo',
            'FINSWIMMINGJUDGE' => 'Oficial Técnico de Natação com Barbatanas',
            'FREEDIVINGJUDGE' => 'Oficial Técnico de Mergulho Livre',
            'ORIENTEERINGREFEREE' => 'Oficial Técnico de Orientação',
            'SPEARFISHINGJUDGE' => 'Oficial Técnico de Pesca Submarina',
            'SPORTDIVINGJUDGE' => 'Oficial Técnico de Mergulho Desportivo',
            'TARGETSHOOTINGJUDGE' => 'Oficial Técnico de Tiro ao Alvo',
            'UNDERWATERHOCKEYREFEREE' => 'Oficial Técnico de Hóquei Subaquático',
            'UNDERWATERRUGBYREFEREE' => 'Oficial Técnico de Rugby Subaquático',
            'VISUALJURY' => 'Oficial Técnico de Audiovisuais',
        ];

        foreach ($updates as $code => $newName) {
            ProfessionalRole::where('code', $code)->update(['name' => $newName]);
        }
    }

    public function down(): void
    {
        $rollback = [
            'AQUATHLONREFEREE' => 'Árbitro de Aquatlo',
            'FINSWIMMINGJUDGE' => 'Juiz de Natação com Barbatanas',
            'FREEDIVINGJUDGE' => 'Juiz de Mergulho Livre',
            'ORIENTEERINGREFEREE' => 'Árbitro de Orientação',
            'SPEARFISHINGJUDGE' => 'Juiz de Pesca Submarina',
            'SPORTDIVINGJUDGE' => 'Juiz de Mergulho Desportivo',
            'TARGETSHOOTINGJUDGE' => 'Juiz de Tiro ao Alvo',
            'UNDERWATERHOCKEYREFEREE' => 'Árbitro de Hóquei Subaquático',
            'UNDERWATERRUGBYREFEREE' => 'Árbitro de Rugby Subaquático',
            'VISUALJURY' => 'Juiz de Audiovisuais',
        ];

        foreach ($rollback as $code => $originalName) {
            ProfessionalRole::where('code', $code)->update(['name' => $originalName]);
        }
    }
};
