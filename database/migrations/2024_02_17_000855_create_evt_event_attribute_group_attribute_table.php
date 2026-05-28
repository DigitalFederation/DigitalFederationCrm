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
        Schema::create('evt_attribute_groups_attribute', function (Blueprint $table) {
            $table->foreignId('attribute_group_id')->constrained('evt_attribute_groups');
            $table->foreignId('attribute_id')->constrained('evt_attributes');
            $table->unique(['attribute_group_id', 'attribute_id'], 'evt_attr_grp_attr_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_attribute_groups_attribute');
    }
};
