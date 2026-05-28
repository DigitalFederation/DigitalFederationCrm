<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Schema table name to migrate
     *
     * @var string
     */
    public $tableName = 'country';

    /**
     * Run the migrations.
     *
     * @table country
     */
    public function up(): void
    {
        Schema::create('country', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('region_name', 100);
            $table->string('sub_region_name', 100);
            $table->char('ioc', 3);
            $table->char('continent', 2);
            $table->tinyInteger('supported')->default(1);
            $table->unsignedBigInteger('geo_zone_id')->nullable();
            $table->unsignedBigInteger('sub_region_id')->nullable();

            $table->foreign('geo_zone_id')->references('id')->on('geo_zones');
            $table->foreign('sub_region_id')->references('id')->on('sub_regions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country');
    }
};
