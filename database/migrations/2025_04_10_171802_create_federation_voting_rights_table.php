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
        Schema::create('federation_voting_rights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('federation_id')->constrained('federation')->onDelete('cascade');
            $table->unsignedSmallInteger('year');

            $statusEnum = ['Voting right', 'Suspended', 'Probation', 'No Voting Right'];

            // General Assembly
            $table->enum('general_assembly_status', $statusEnum)->default('No Voting Right');

            // Committees
            $table->enum('technical_committee_status', $statusEnum)->default('No Voting Right');
            $table->enum('scientific_committee_status', $statusEnum)->default('No Voting Right');
            $table->enum('sport_committee_status', $statusEnum)->default('No Voting Right');

            // Sport Commissions
            $table->enum('finswimming_commission_status', $statusEnum)->default('No Voting Right');
            $table->enum('freediving_commission_status', $statusEnum)->default('No Voting Right');
            $table->enum('aquathlon_commission_status', $statusEnum)->default('No Voting Right');
            $table->enum('underwater_hockey_commission_status', $statusEnum)->default('No Voting Right');
            $table->enum('underwater_rugby_commission_status', $statusEnum)->default('No Voting Right');
            $table->enum('target_shooting_commission_status', $statusEnum)->default('No Voting Right');
            $table->enum('sport_diving_commission_status', $statusEnum)->default('No Voting Right');
            $table->enum('spearfishing_commission_status', $statusEnum)->default('No Voting Right');
            $table->enum('orienteering_commission_status', $statusEnum)->default('No Voting Right');
            $table->enum('visual_commission_status', $statusEnum)->default('No Voting Right');

            $table->unique(['federation_id', 'year']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('federation_voting_rights');
    }
};
