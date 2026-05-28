<?php

use App\Models\Committee;
use App\Models\MenuItem;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fix incorrect committee_id values on federation menu items.
     * The menu items had wrong committee associations which caused
     * the sidebar to filter out menus when URL contained filter[committee].
     */
    public function up(): void
    {
        // Get committee IDs by code
        $sportId = Committee::where('code', 'SPORT')->value('id');
        $divingId = Committee::where('code', 'DIVING')->value('id');
        $scientificId = Committee::where('code', 'SCIENTIFIC')->value('id');

        // Fix "Desporto Subaquatico" - should be SPORT (was DIVINGSERVICES)
        MenuItem::where('id', 123)->update(['committee_id' => $sportId]);

        // Fix "CMAS Mergulho Recreativo" - should be DIVING (was SCIENTIFIC)
        MenuItem::where('id', 130)->update(['committee_id' => $divingId]);

        // Fix "CMAS Mergulho Cientifico" - should be SCIENTIFIC (was SPORT)
        MenuItem::where('id', 135)->update(['committee_id' => $scientificId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get committee IDs by code
        $sportId = Committee::where('code', 'SPORT')->value('id');
        $divingServicesId = Committee::where('code', 'DIVINGSERVICES')->value('id');
        $scientificId = Committee::where('code', 'SCIENTIFIC')->value('id');

        // Restore original (incorrect) values
        MenuItem::where('id', 123)->update(['committee_id' => $divingServicesId]);
        MenuItem::where('id', 130)->update(['committee_id' => $scientificId]);
        MenuItem::where('id', 135)->update(['committee_id' => $sportId]);
    }
};
