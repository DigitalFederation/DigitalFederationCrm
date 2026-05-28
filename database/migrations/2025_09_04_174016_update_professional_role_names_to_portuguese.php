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
        $translations = [
            'SCIENTIFICDIVER' => 'Mergulhador Científico',
            'SCIENTIFICINSTRUCTOR' => 'Instrutor Científico',
            'SCIENTIFICSPECIALITYDIVER' => 'Mergulhador de Especialidade Científica',
            'SCIENTIFICSPECIALITYINSTRUCTOR' => 'Instrutor de Especialidade Científica',
            'SCIENTIFICDIVELEADER' => 'Líder de Mergulho Científico',
            'DIVELEADER' => 'Líder de Mergulho',
            'DIVER' => 'Mergulhador',
            'DIVINGINSTRUCTOR' => 'Instrutor de Mergulho',
            'FREEDIVER' => 'Mergulhador Livre',
            'FREEDIVERINSTRUCTOR' => 'Instrutor de Mergulho Livre',
            'TECHNICALDIVER' => 'Mergulhador Técnico',
            'TECHNICALDIVINGINSTRUCTOR' => 'Instrutor de Mergulho Técnico',
            'AQUATHLONCOACH' => 'Treinador de Aquatlo',
            'FINSWIMMINGCOACH' => 'Treinador de Natação com Barbatanas',
            'FREEDIVINGCOACH' => 'Treinador de Mergulho Livre',
            'ORIENTEERINGCOACH' => 'Treinador de Orientação',
            'SPEARFISHINGCOACH' => 'Treinador de Pesca Submarina',
            'SPORTDIVINGCOACH' => 'Treinador de Mergulho Desportivo',
            'TARGETSHOOTINGCOACH' => 'Treinador de Tiro ao Alvo',
            'UNDERWATERHOCKEYCOACH' => 'Treinador de Hóquei Subaquático',
            'VISUALCOACH' => 'Treinador de Imagem',
            'AQUATHLONREFEREE' => 'Árbitro de Aquatlo',
            'FINSWIMMINGJUDGE' => 'Juiz de Natação com Barbatanas',
            'FREEDIVINGJUDGE' => 'Juiz de Mergulho Livre',
            'ORIENTEERINGREFEREE' => 'Árbitro de Orientação',
            'SPEARFISHINGJUDGE' => 'Juiz de Pesca Submarina',
            'SPORTDIVINGJUDGE' => 'Juiz de Mergulho Desportivo',
            'TARGETSHOOTINGJUDGE' => 'Juiz de Tiro ao Alvo',
            'UNDERWATERHOCKEYREFEREE' => 'Árbitro de Hóquei Subaquático',
            'UNDERWATERRUGBYREFEREE' => 'Árbitro de Rugby Subaquático',
            'UNDERWATERRUGBYCOACH' => 'Treinador de Rugby Subaquático',
            'ATHLETE' => 'Atleta',
            'DIVINGPROFESSIONAL' => 'Profissional de Mergulho',
        ];

        foreach ($translations as $code => $portugueseName) {
            DB::table('professional_roles')
                ->where('code', $code)
                ->update(['name' => $portugueseName]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $originalNames = [
            'SCIENTIFICDIVER' => 'Scientific Diver',
            'SCIENTIFICINSTRUCTOR' => 'Scientific Instructor',
            'SCIENTIFICSPECIALITYDIVER' => 'Scientific Speciality Diver',
            'SCIENTIFICSPECIALITYINSTRUCTOR' => 'Scientific Speciality Instructor',
            'SCIENTIFICDIVELEADER' => 'Scientific Dive Leader',
            'DIVELEADER' => 'Dive Leader',
            'DIVER' => 'Diver',
            'DIVINGINSTRUCTOR' => 'Diving Instructor',
            'FREEDIVER' => 'Freediver',
            'FREEDIVERINSTRUCTOR' => 'Freediving Instructor',
            'TECHNICALDIVER' => 'Technical Diver',
            'TECHNICALDIVINGINSTRUCTOR' => 'Technical Diving Instructor',
            'AQUATHLONCOACH' => 'Aquathlon Coach',
            'FINSWIMMINGCOACH' => 'Finswimming Coach',
            'FREEDIVINGCOACH' => 'Freediving Coach',
            'ORIENTEERINGCOACH' => 'Orienteering Coach',
            'SPEARFISHINGCOACH' => 'Spearfishing Coach',
            'SPORTDIVINGCOACH' => 'Sport Diving Coach',
            'TARGETSHOOTINGCOACH' => 'Target Shooting Coach',
            'UNDERWATERHOCKEYCOACH' => 'Underwater Hockey Coach',
            'VISUALCOACH' => 'Visual Coach',
            'AQUATHLONREFEREE' => 'Aquathlon Referee',
            'FINSWIMMINGJUDGE' => 'Finswimming Judge',
            'FREEDIVINGJUDGE' => 'Freediving Judge',
            'ORIENTEERINGREFEREE' => 'Orienteering Referee',
            'SPEARFISHINGJUDGE' => 'Spearfishing Judge',
            'SPORTDIVINGJUDGE' => 'Sport Diving Judge',
            'TARGETSHOOTINGJUDGE' => 'Target Shooting Judge',
            'UNDERWATERHOCKEYREFEREE' => 'Underwater Hockey Referee',
            'UNDERWATERRUGBYREFEREE' => 'Underwater Rugby Referee',
            'UNDERWATERRUGBYCOACH' => 'Underwater Rugby Coach',
            'ATHLETE' => 'Athlete',
            'DIVINGPROFESSIONAL' => 'Diving Professional',
        ];

        foreach ($originalNames as $code => $englishName) {
            DB::table('professional_roles')
                ->where('code', $code)
                ->update(['name' => $englishName]);
        }
    }
};
