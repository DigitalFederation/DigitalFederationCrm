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
        // Rename the table
        Schema::rename('diving_technical_director_invitations', 'diving_entity_technical_directors');

        // Modify the renamed table
        Schema::table('diving_entity_technical_directors', function (Blueprint $table) {
            // Rename column from invitation context to assignment context
            $table->renameColumn('invitation_sent_at', 'assigned_at');

            // Drop invitation-specific columns that are no longer needed
            $table->dropColumn(['responded_at', 'response_notes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the columns first
        Schema::table('diving_entity_technical_directors', function (Blueprint $table) {
            // Add back the dropped columns
            $table->timestamp('responded_at')->nullable()->after('assigned_at');
            $table->text('response_notes')->nullable()->after('responded_at');

            // Rename column back to invitation context
            $table->renameColumn('assigned_at', 'invitation_sent_at');
        });

        // Rename table back to original name
        Schema::rename('diving_entity_technical_directors', 'diving_technical_director_invitations');
    }
};
