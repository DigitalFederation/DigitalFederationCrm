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
        // Use raw SQL to alter the ENUM column
        // This is necessary because Laravel doesn't have a direct method to modify ENUM values
        DB::statement("ALTER TABLE diving_professional_certifications 
                      MODIFY COLUMN certification_system 
                      ENUM('SSI', 'PADI', 'SDI_TDI', 'DDI', 'GUE', 'CMAS') 
                      NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove CMAS from the ENUM values
        DB::statement("ALTER TABLE diving_professional_certifications 
                      MODIFY COLUMN certification_system 
                      ENUM('SSI', 'PADI', 'SDI_TDI', 'DDI', 'GUE') 
                      NOT NULL");
    }
};
