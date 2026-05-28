<?php

use App\Enums\MembershipTargetType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('membership_packages', function (Blueprint $table) {
            $table->json('distribution_methods')->nullable()->after('target_type');
        });

        // Migrate existing data
        DB::table('membership_packages')->get()->each(function ($package) {
            $distributionMethods = [];

            switch ($package->target_type) {
                case MembershipTargetType::INDIVIDUAL->value:
                    // Individual packages were available directly
                    $distributionMethods = ['direct'];
                    break;

                case MembershipTargetType::ENTITY->value:
                    // Entity packages were available directly
                    $distributionMethods = ['direct'];
                    break;

                case MembershipTargetType::BOTH->value:
                    // Both means individual packages available both ways
                    // Update target_type to 'individual' with both distribution methods
                    DB::table('membership_packages')
                        ->where('id', $package->id)
                        ->update(['target_type' => MembershipTargetType::INDIVIDUAL->value]);
                    $distributionMethods = ['direct', 'entity_managed'];
                    break;

                case MembershipTargetType::INDIVIDUAL_FROM_ENTITY->value:
                    // Individual packages only available through entities
                    DB::table('membership_packages')
                        ->where('id', $package->id)
                        ->update(['target_type' => MembershipTargetType::INDIVIDUAL->value]);
                    $distributionMethods = ['entity_managed'];
                    break;
            }

            DB::table('membership_packages')
                ->where('id', $package->id)
                ->update(['distribution_methods' => json_encode($distributionMethods)]);
        });

        // Make distribution_methods required after migration
        Schema::table('membership_packages', function (Blueprint $table) {
            $table->json('distribution_methods')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First restore the original target_type values based on distribution_methods
        DB::table('membership_packages')->get()->each(function ($package) {
            $distributionMethods = json_decode($package->distribution_methods, true) ?? [];
            $currentTargetType = $package->target_type;

            if ($currentTargetType === MembershipTargetType::INDIVIDUAL->value) {
                if (in_array('direct', $distributionMethods) && in_array('entity_managed', $distributionMethods)) {
                    // Both distribution methods = BOTH
                    DB::table('membership_packages')
                        ->where('id', $package->id)
                        ->update(['target_type' => MembershipTargetType::BOTH->value]);
                } elseif (in_array('entity_managed', $distributionMethods) && ! in_array('direct', $distributionMethods)) {
                    // Only entity_managed = INDIVIDUAL_FROM_ENTITY
                    DB::table('membership_packages')
                        ->where('id', $package->id)
                        ->update(['target_type' => MembershipTargetType::INDIVIDUAL_FROM_ENTITY->value]);
                }
                // else leave as INDIVIDUAL (direct only)
            }
            // ENTITY target_type remains unchanged
        });

        Schema::table('membership_packages', function (Blueprint $table) {
            $table->dropColumn('distribution_methods');
        });
    }
};
