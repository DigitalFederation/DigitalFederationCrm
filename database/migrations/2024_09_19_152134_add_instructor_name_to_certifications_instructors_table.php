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
        Schema::table('certifications_attributed_instructors', function (Blueprint $table) {
            $table->string('instructor_name')->after('individual_id');
        });
    }

};
