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
        Schema::create('diving_technical_director_invitations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('entity_id')->constrained('entity')->onDelete('cascade');
            $table->foreignUuid('individual_id')->constrained('individual')->onDelete('cascade');
            $table->uuid('license_attributed_id');
            $table->foreign('license_attributed_id', 'diving_invite_license_fk')->references('id')->on('license_attributed')->onDelete('cascade');
            $table->json('certification_systems');
            $table->string('status_class');
            $table->timestamp('invitation_sent_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->text('response_notes')->nullable();
            $table->timestamps();

            $table->index(['entity_id', 'individual_id'], 'diving_invite_entity_individual_idx');
            $table->index('status_class', 'diving_invite_status_idx');
            $table->index('license_attributed_id', 'diving_invite_license_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diving_technical_director_invitations');
    }
};
