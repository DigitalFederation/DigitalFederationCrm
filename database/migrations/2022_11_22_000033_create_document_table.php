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
    public $tableName = 'document';

    /**
     * Run the migrations.
     *
     * @table document
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('type_id')->constrained('document_type');
            $table->string('status_class', 200);
            $table->text('customer_name')->nullable();
            $table->nullableUuidMorphs('owner');
            $table->string('tax_number', 20)->nullable();
            $table->decimal('net_value', 12)->nullable();
            $table->decimal('tax_value', 12)->nullable();
            $table->decimal('tax_percentage', 5)->nullable();
            $table->decimal('total_value', 12)->nullable();
            $table->foreignId('method_id')->nullable()->constrained('payment_method');
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('updated_by')->nullable()->constrained('users');
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
