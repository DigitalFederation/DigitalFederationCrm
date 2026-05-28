<?php

use Domain\Documents\Models\DocumentDetail;
use Domain\EvtEvents\Models\Enrollment;
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
        Schema::table('evt_enrollments', function (Blueprint $table) {
            $table->uuid('document_id')->nullable()->after('pricing_id');
            $table->foreign('document_id')
                ->references('id')
                ->on('document')
                ->onDelete('set null');
        });

        // Migrate existing relationships
        $documentDetails = DocumentDetail::where('owner_type', Enrollment::class)
            ->with('document')
            ->get();

        foreach ($documentDetails as $detail) {
            if ($detail->document) {
                Enrollment::where('id', $detail->owner_id)
                    ->update(['document_id' => $detail->document_id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_enrollments', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->dropColumn('document_id');
        });
    }
};
