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
        // Drop pivot table first (has foreign keys)
        Schema::dropIfExists('evt_federation_candidacy_winners');

        // Drop related tables
        Schema::dropIfExists('evt_federation_candidacy_attachments');
        Schema::dropIfExists('evt_federation_candidacy_attributes');

        // Drop main candidacy table
        Schema::dropIfExists('evt_federation_candidacies');

        // Remove candidacy columns from events table
        Schema::table('evt_events', function (Blueprint $table) {
            $table->dropColumn('candidacy_limit_date');
            $table->dropColumn('candidacy_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate events columns
        Schema::table('evt_events', function (Blueprint $table) {
            $table->date('candidacy_limit_date')->nullable();
            $table->decimal('candidacy_fee', 8, 2)->nullable();
        });

        // Recreate candidacy table
        Schema::create('evt_federation_candidacies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('federation_id');
            $table->unsignedBigInteger('event_id');
            $table->text('interest_statement')->nullable();
            $table->boolean('fee_paid')->default(false);
            $table->string('status_class')->nullable();
            $table->timestamps();

            $table->foreign('federation_id')->references('id')->on('federations');
            $table->foreign('event_id')->references('id')->on('evt_events');
        });

        // Recreate attributes table
        Schema::create('evt_federation_candidacy_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidacy_id');
            $table->unsignedBigInteger('attribute_id');
            $table->string('value');
            $table->timestamps();

            $table->foreign('candidacy_id')->references('id')->on('evt_federation_candidacies');
            $table->foreign('attribute_id')->references('id')->on('evt_attributes');
        });

        // Recreate attachments table
        Schema::create('evt_federation_candidacy_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidacy_id');
            $table->string('file_name');
            $table->string('mime_type');
            $table->string('disk');
            $table->unsignedInteger('size');
            $table->unsignedInteger('order_column')->nullable();
            $table->timestamps();

            $table->foreign('candidacy_id')->references('id')->on('evt_federation_candidacies');
        });

        // Recreate winners table
        Schema::create('evt_federation_candidacy_winners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('federation_candidacy_id');
            $table->unsignedBigInteger('event_id');
            $table->timestamps();

            $table->foreign('federation_candidacy_id')->references('id')->on('evt_federation_candidacies');
            $table->foreign('event_id')->references('id')->on('evt_events');
        });
    }
};
