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
        // Normalize requester_model values to use lowercase enum values
        // The RequesterTypeEnum expects: 'individual', 'entity', 'federation'

        // Update ["Entity"] to ["entity"]
        DB::table('license')
            ->where('requester_model', '["Entity"]')
            ->update(['requester_model' => '["entity"]']);

        // Update ["Individual"] to ["individual"]
        DB::table('license')
            ->where('requester_model', '["Individual"]')
            ->update(['requester_model' => '["individual"]']);

        // Update ["Federation"] to ["federation"]
        DB::table('license')
            ->where('requester_model', '["Federation"]')
            ->update(['requester_model' => '["federation"]']);

        // Update mixed cases
        DB::table('license')
            ->where('requester_model', '["Individual","Entity"]')
            ->update(['requester_model' => '["individual","entity"]']);

        DB::table('license')
            ->where('requester_model', '["Entity","Individual"]')
            ->update(['requester_model' => '["entity","individual"]']);

        DB::table('license')
            ->where('requester_model', '["Individual","Entity","Federation"]')
            ->update(['requester_model' => '["individual","entity","federation"]']);

        DB::table('license')
            ->where('requester_model', '["Entity","Individual","Federation"]')
            ->update(['requester_model' => '["entity","individual","federation"]']);

        DB::table('license')
            ->where('requester_model', '["Individual", "Entity", "Federation"]')
            ->update(['requester_model' => '["individual","entity","federation"]']);

        // Also handle with spaces
        DB::table('license')
            ->where('requester_model', '["Individual", "Entity"]')
            ->update(['requester_model' => '["individual","entity"]']);

        DB::table('license')
            ->where('requester_model', '["Entity", "Individual"]')
            ->update(['requester_model' => '["entity","individual"]']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to capitalized form (though this might not be needed)
        DB::table('license')
            ->where('requester_model', '["entity"]')
            ->update(['requester_model' => '["Entity"]']);

        DB::table('license')
            ->where('requester_model', '["individual"]')
            ->update(['requester_model' => '["Individual"]']);

        DB::table('license')
            ->where('requester_model', '["federation"]')
            ->update(['requester_model' => '["Federation"]']);

        DB::table('license')
            ->where('requester_model', '["individual","entity"]')
            ->update(['requester_model' => '["Individual","Entity"]']);

        DB::table('license')
            ->where('requester_model', '["entity","individual"]')
            ->update(['requester_model' => '["Entity","Individual"]']);

        DB::table('license')
            ->where('requester_model', '["individual","entity","federation"]')
            ->update(['requester_model' => '["Individual","Entity","Federation"]']);

        DB::table('license')
            ->where('requester_model', '["entity","individual","federation"]')
            ->update(['requester_model' => '["Entity","Individual","Federation"]']);
    }
};
