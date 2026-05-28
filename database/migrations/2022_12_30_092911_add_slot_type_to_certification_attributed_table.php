<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certification_attributed', function (Blueprint $table) {
            $table->foreignId('slot_type_id')->nullable()->constrained('certifications_slot_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certification_attributed', function (Blueprint $table) {
            $table->dropColumn('slot_type_id');
        });
    }
};
