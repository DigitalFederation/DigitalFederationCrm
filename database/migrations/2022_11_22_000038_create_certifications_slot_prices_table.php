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
    public $tableName = 'certifications_slot_prices';

    /**
     * Run the migrations.
     *
     * @table certifications_slot_prices
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_id')->constrained('certification');
            $table->unsignedSmallInteger('quantity')->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->integer('discount')->nullable()->unsigned()->default('0')->comment('percentage discount');
            $table->tinyInteger('active')->nullable();

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
