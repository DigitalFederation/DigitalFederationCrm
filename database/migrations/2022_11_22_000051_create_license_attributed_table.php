<?php

namespace Database\Migrations;

use Domain\Licenses\Models\License;
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
    public $tableName = 'license_attributed';

    /**
     * Run the migrations.
     *
     * @table license_attributed
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('status_class');
            $table->foreignId('license_id')->constrained('license');
            $table->foreignId('federation_id')->constrained('federation');
            $table->nullableUuidMorphs('model');
            $table->string('license_name', 150)->nullable();
            $table->string('holder_name', 150)->nullable();
            $table->string('federation_name', 200)->nullable();
            $table->string('national_license_code', 45)->nullable();
            $table->string('cmas_license_code', 45)->nullable();
            $table->decimal('total_value', 12, 2)->nullable();
            $table->dateTime('activated_at')->nullable();
            $table->date('current_term_starts_at')->nullable();
            $table->date('current_term_ends_at')->nullable();
            $table->dateTime('last_billing_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('updated_by')->nullable()->constrained('users');
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
        Schema::dropIfExists($this->tableName);
    }
};
