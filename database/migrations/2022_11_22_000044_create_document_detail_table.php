<?php

namespace Database\Migrations;

use Domain\Documents\Models\Document;
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
    public $tableName = 'document_detail';

    /**
     * Run the migrations.
     *
     * @table document_detail
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('document_id')->constrained('document');
            $table->text('description')->nullable();
            $table->nullableUuidMorphs('owner');
            $table->text('reference')->nullable();
            $table->smallInteger('quantity')->nullable()->default('1');
            $table->decimal('net_value', 12, 2)->nullable();
            $table->decimal('tax_value', 12, 2)->nullable();
            $table->decimal('tax_percentage', 5, 2)->nullable();
            $table->decimal('total_value', 12, 2)->nullable();
            $table->tinyInteger('is_debit')->nullable();

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
