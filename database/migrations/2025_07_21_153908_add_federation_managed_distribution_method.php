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
        // Update existing membership packages that might benefit from federation_managed
        // This migration doesn't change the structure, just adds the new option as valid
        // The actual addition of 'federation_managed' to packages will be done through the UI

        // Note: distribution_methods is a JSON column, so we don't need to alter the column type
        // We just need to ensure the application validates and accepts 'federation_managed' as a valid value
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'federation_managed' from any packages that have it
        $packages = DB::table('membership_packages')
            ->whereRaw("JSON_CONTAINS(distribution_methods, '\"federation_managed\"')")
            ->get();

        foreach ($packages as $package) {
            $methods = json_decode($package->distribution_methods, true);
            $methods = array_filter($methods, function ($method) {
                return $method !== 'federation_managed';
            });

            // Ensure at least 'direct' remains if no other methods
            if (empty($methods)) {
                $methods = ['direct'];
            }

            DB::table('membership_packages')
                ->where('id', $package->id)
                ->update(['distribution_methods' => json_encode(array_values($methods))]);
        }
    }
};
