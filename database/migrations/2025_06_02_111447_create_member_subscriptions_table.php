<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_subscriptions', function (Blueprint $table) {
            $table->id();

            // who is subscribed – UUID morph keeps it in-line with `insurances.member`
            $table->uuidMorphs('member');               // {member_type, member_id}

            // what they subscribed to
            $table->foreignId('membership_package_id')
                ->constrained('membership_packages')
                ->onDelete('cascade');
            $table->string('status_class');
            // lifecycle
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_subscriptions');
    }
};
