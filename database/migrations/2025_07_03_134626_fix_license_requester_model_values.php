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
        // Update lowercase 'entity' to full class name
        DB::table('license')
            ->where('requester_model', 'entity')
            ->update(['requester_model' => 'Domain\Entities\Models\Entity']);

        // Update lowercase 'individual' to full class name
        DB::table('license')
            ->where('requester_model', 'individual')
            ->update(['requester_model' => 'Domain\Individuals\Models\Individual']);

        // Update lowercase 'federation' to full class name
        DB::table('license')
            ->where('requester_model', 'federation')
            ->update(['requester_model' => 'Domain\Federations\Models\Federation']);

        // Update lowercase 'all' to capitalized 'All'
        DB::table('license')
            ->where('requester_model', 'all')
            ->update(['requester_model' => 'All']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to lowercase values
        DB::table('license')
            ->where('requester_model', 'Domain\Entities\Models\Entity')
            ->update(['requester_model' => 'entity']);

        DB::table('license')
            ->where('requester_model', 'Domain\Individuals\Models\Individual')
            ->update(['requester_model' => 'individual']);

        DB::table('license')
            ->where('requester_model', 'Domain\Federations\Models\Federation')
            ->update(['requester_model' => 'federation']);

        DB::table('license')
            ->where('requester_model', 'All')
            ->update(['requester_model' => 'all']);
    }
};
