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
        Schema::create('certifications_attributed_instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('attributed_id')->constrained('certification_attributed');
            $table->foreignUuid('individual_id')->constrained('individual');
            $table->boolean('is_main')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certifications_attributed_instructors');
    }
};
