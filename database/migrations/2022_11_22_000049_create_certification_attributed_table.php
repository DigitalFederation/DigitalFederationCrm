<?php

namespace Database\Migrations;

use Domain\Certifications\Models\Certification;
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
    public $tableName = 'certification_attributed';

    /**
     * Run the migrations.
     *
     * @table certification_attributed
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->uuid('id')->primary();
            $table->foreignId('certification_id')->constrained('certification');
            $table->foreignId('federation_id')->constrained('federation');
            $table->text('status_class');
            $table->foreignUuid('individual_id')->constrained('individual');
            $table->string('certification_name', 45)->nullable();
            $table->string('holder_name', 45)->nullable();
            $table->string('federation_name', 150)->nullable();
            $table->string('code', 45)->nullable();
            $table->string('number', 45)->nullable();

            $table->char('activator_id', 36)->nullable()->index();
            $table->string('activator_type', 255)->nullable()->index();

            $table->dateTime('activated_at')->nullable();
            $table->date('current_term_starts_at')->nullable();
            $table->date('current_term_ends_at')->nullable();
            $table->dateTime('last_billing_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('updated_by')->nullable()->constrained('users');
            $table->nullableTimestamps();
            $table->softDeletes();
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
