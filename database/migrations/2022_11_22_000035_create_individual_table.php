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
    public $tableName = 'individual';

    /**
     * Run the migrations.
     *
     * @table individual
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('country_id')->constrained('country');
            $table->foreignUuid('user_id')->constrained('users');
            $table->string('national_federation_number', 45)->nullable();
            $table->string('name', 45);
            $table->string('surname', 45)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('email', 200);
            $table->string('doc_ref_type', 45)->nullable()->comment('passport, cc, etc.');
            $table->string('doc_ref', 45)->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('updated_by')->nullable()->constrained('users');
            $table->softDeletes();

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
