<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_packages', function (Blueprint $table) {
            $table->id();

            // Keep nullable for now so we don’t need the federations table yet.
            $table->unsignedBigInteger('federation_id')->nullable();

            $table->string('name');
            $table->text('description')->nullable();

            // e.g. “individual”, “entity”… keep flexible
            $table->string('target_type')->nullable();

            $table->boolean('is_active')->default(true);
            $table->string('version')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_packages');
    }
};
