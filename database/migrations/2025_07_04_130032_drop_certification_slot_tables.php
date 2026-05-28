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
        // Drop foreign key constraints first
        Schema::table('certification_attributed', function (Blueprint $table) {
            if (Schema::hasColumn('certification_attributed', 'slot_type_id')) {
                $table->dropForeign(['slot_type_id']);
            }
        });

        // Drop the slot-related tables
        Schema::dropIfExists('certifications_slot');
        Schema::dropIfExists('certifications_slot_prices');
        Schema::dropIfExists('certifications_slot_type');

        // Remove slot_type_id from certification_attributed table
        Schema::table('certification_attributed', function (Blueprint $table) {
            if (Schema::hasColumn('certification_attributed', 'slot_type_id')) {
                $table->dropColumn('slot_type_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate certifications_slot_type table
        Schema::create('certifications_slot_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_shipping')->default(false);
            $table->timestamps();
        });

        // Recreate certifications_slot_prices table
        Schema::create('certifications_slot_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_id')->constrained('certification');
            $table->integer('quantity_min');
            $table->integer('quantity_max');
            $table->decimal('unit_value', 10, 2);
            $table->decimal('tax_value', 10, 2)->nullable();
            $table->decimal('tax_percentage', 5, 2)->nullable();
            $table->foreignId('slot_type')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Recreate certifications_slot table
        Schema::create('certifications_slot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('federation_id')->constrained('federations');
            $table->foreignId('certification_id')->constrained('certification');
            $table->string('status_class');
            $table->foreignId('price_id')->nullable()->constrained('certifications_slot_prices');
            $table->integer('quantity_original');
            $table->integer('quantity_real');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->text('description')->nullable();
            $table->datetime('shipped_date')->nullable();
            $table->string('delivery_method')->nullable();
            $table->integer('order')->nullable();
            $table->foreignId('certifications_slot_type_id')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_location')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country_zone')->nullable();
            $table->string('shipping_country')->nullable();
            $table->foreignId('shipping_method_id')->nullable();
            $table->foreignId('shipping_zone_id')->nullable();
            $table->decimal('shipping_price', 10, 2)->nullable();
            $table->integer('numeration_from')->nullable();
            $table->integer('numeration_to')->nullable();
            $table->string('settlement_type')->nullable();
            $table->text('settlement_notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Re-add slot_type_id to certification_attributed table
        Schema::table('certification_attributed', function (Blueprint $table) {
            $table->foreignId('slot_type_id')->nullable()->after('instructor_id')
                ->constrained('certifications_slot_type');
        });
    }
};
