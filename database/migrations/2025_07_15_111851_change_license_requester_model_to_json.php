<?php

use Domain\Licenses\Actions\NormalizeRequesterTypeAction;
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
        // First, convert existing data to JSON format in a temporary column
        Schema::table('license', function (Blueprint $table) {
            $table->json('requester_model_json')->nullable()->after('requester_model');
        });

        // Convert existing single values to normalized arrays using the action
        $licenses = DB::table('license')->whereNotNull('requester_model')->get();
        $action = new NormalizeRequesterTypeAction;

        foreach ($licenses as $license) {
            // Use the action to normalize the value
            $normalized = $action($license->requester_model);

            DB::table('license')
                ->where('id', $license->id)
                ->update(['requester_model_json' => $normalized !== null ? json_encode($normalized) : null]);
        }

        // Drop the old column and rename the new one
        Schema::table('license', function (Blueprint $table) {
            $table->dropColumn('requester_model');
        });

        Schema::table('license', function (Blueprint $table) {
            $table->renameColumn('requester_model_json', 'requester_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Create temporary column for string value
        Schema::table('license', function (Blueprint $table) {
            $table->string('requester_model_string')->nullable()->after('requester_model');
        });

        // Convert JSON arrays back to single values
        $licenses = DB::table('license')->whereNotNull('requester_model')->get();

        foreach ($licenses as $license) {
            $jsonValue = json_decode($license->requester_model, true);
            $stringValue = null;

            if (is_array($jsonValue)) {
                // If it has all three, it's 'All'
                if (count($jsonValue) === 3 &&
                    in_array('Individual', $jsonValue) &&
                    in_array('Entity', $jsonValue) &&
                    in_array('Federation', $jsonValue)) {
                    $stringValue = 'All';
                } elseif (count($jsonValue) === 1) {
                    // Single value - convert to full class name
                    switch ($jsonValue[0]) {
                        case 'Individual':
                            $stringValue = 'Domain\Individuals\Models\Individual';
                            break;
                        case 'Entity':
                            $stringValue = 'Domain\Entities\Models\Entity';
                            break;
                        case 'Federation':
                            $stringValue = 'Domain\Federations\Models\Federation';
                            break;
                    }
                } else {
                    // For combinations, default to 'All' to avoid data loss
                    $stringValue = 'All';
                }
            }

            DB::table('license')
                ->where('id', $license->id)
                ->update(['requester_model_string' => $stringValue]);
        }

        // Drop the JSON column and rename string column back
        Schema::table('license', function (Blueprint $table) {
            $table->dropColumn('requester_model');
        });

        Schema::table('license', function (Blueprint $table) {
            $table->renameColumn('requester_model_string', 'requester_model');
        });
    }
};
