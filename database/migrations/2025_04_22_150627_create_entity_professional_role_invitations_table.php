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
        // Renamed table to be more specific and follow convention
        Schema::create('entity_professional_role_invitations', function (Blueprint $table) {
            $table->id();
            // Use constrained foreignId for better relationship definition
            $table->foreignId('inviting_entity_id')->constrained('entity')->cascadeOnDelete();
            $table->foreignUUId('invited_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('committee_code'); // e.g., 'DIVING', 'SCIENTIFIC' - Context of the invitation
            $table->string('status')->default('pending'); // e.g., pending, accepted, rejected, expired
            $table->timestamp('expires_at')->nullable(); // Optional: If invites should expire (like signed URL)
            $table->timestamps(); // created_at, updated_at

            // Index for faster lookups by the invited user and status
            $table->index(['invited_user_id', 'status'], 'prof_role_invites_user_status_idx');
            // Unique constraint to prevent duplicate pending invites for the same entity, user, and committee
            $table->unique(['inviting_entity_id', 'invited_user_id', 'committee_code', 'status'], 'unique_pending_prof_role_invite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_professional_role_invitations');
    }
};
