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
    public $tableName = 'certifications_slot';

    /**
     * Run the migrations.
     *
     * @table certifications_slot
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('federation_id')->constrained('federation');
            $table->foreignId('certification_id')->constrained('certification');
            $table->foreignId('price_id')->constrained('certifications_slot_prices');

            $table->string('status_class', 200)->nullable();
            $table->unsignedInteger('quantity_original')->nullable()->default('0');
            $table->unsignedInteger('quantity_real')->nullable()->default('0');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->text('description')->nullable();

            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
};
