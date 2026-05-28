<?php

use App\Enums\EvtAthleteEnrollmentStatusEnum;
use Domain\EvtEvents\Models\AthleteEnrollment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration fixes legacy data where athlete enrollments have pending_payment status
     * despite having a discipline assigned. The correct status for these should be DISCIPLINE_ASSIGNED.
     */
    public function up(): void
    {
        // Check if migration has already run by looking for the backup table
        if (Schema::hasTable('athlete_enrollment_status_backups')) {
            Log::info('Migration has already run. Backup table exists.');

            return; // Skip the entire migration if it appears to have been run before
        }

        // Create a backup table to store the original data
        Schema::create('athlete_enrollment_status_backups', function (Blueprint $table) {
            $table->id();
            // Use text fields for IDs to handle both UUIDs and integers
            $table->text('enrollment_id');
            $table->text('individual_id');
            $table->text('event_id');
            $table->text('discipline_id')->nullable();
            $table->string('old_status');
            $table->string('new_status');
            $table->string('payment_status')->nullable();
            $table->decimal('total_price', 10, 2)->nullable(); // Use larger precision
            $table->timestamp('migrated_at');
        });

        // Count potential records before doing any changes
        $recordsCount = AthleteEnrollment::where('status_class', EvtAthleteEnrollmentStatusEnum::PENDING_PAYMENT->value)
            ->whereNotNull('discipline_id')
            ->count();

        Log::info("Found {$recordsCount} athlete enrollments to update");

        // Only proceed if there are records to update
        if ($recordsCount == 0) {
            Log::info('No records to update, migration completed.');

            return;
        }

        // Find all athlete enrollments with pending_payment status that have a discipline
        $affectedEnrollments = AthleteEnrollment::where('status_class', EvtAthleteEnrollmentStatusEnum::PENDING_PAYMENT->value)
            ->whereNotNull('discipline_id')
            ->with('enrollment')
            ->get();

        $count = $affectedEnrollments->count();
        $details = [];

        foreach ($affectedEnrollments as $enrollment) {
            try {
                $enrollmentId = $enrollment->id;
                $eventId = $enrollment->event_id;
                $individualId = $enrollment->individual_id;
                $disciplineId = $enrollment->discipline_id;

                // Safely extract the status values
                $oldStatus = $enrollment->status_class;
                if (is_object($oldStatus) && method_exists($oldStatus, 'value')) {
                    $oldStatus = $oldStatus->value;
                }

                // Use the string value of the enum directly
                $newStatus = EvtAthleteEnrollmentStatusEnum::DISCIPLINE_ASSIGNED->value;
                $totalPrice = $enrollment->total_price;

                // Get payment status safely
                $paymentStatus = null;
                if ($enrollment->enrollment_id) {
                    try {
                        // Try to get the payment status from the enrollment relationship
                        $paymentStatus = $enrollment->enrollment->payment_status ?? null;
                    } catch (\Exception $e) {
                        // If relationship access fails, get payment status directly from DB
                        $baseEnrollment = DB::table('evt_enrollments')
                            ->where('id', $enrollment->enrollment_id)
                            ->first();
                        $paymentStatus = $baseEnrollment ? $baseEnrollment->payment_status : null;

                        Log::warning("Had to bypass relationship for enrollment {$enrollment->id}", [
                            'error' => $e->getMessage(),
                            'enrollment_id' => $enrollment->enrollment_id,
                        ]);
                    }
                }

                // Save the backup data
                try {
                    // Make sure all values are properly cast for the DB
                    $backupData = [
                        'enrollment_id' => (string) $enrollmentId,
                        'individual_id' => (string) $individualId,
                        'event_id' => (string) $eventId,
                        'discipline_id' => $disciplineId ? (string) $disciplineId : null,
                        'old_status' => is_object($oldStatus) ? $oldStatus->value : (string) $oldStatus,
                        'new_status' => is_object($newStatus) ? $newStatus->value : (string) $newStatus,
                        'payment_status' => $paymentStatus ? (string) $paymentStatus : null,
                        'total_price' => $totalPrice ? (float) $totalPrice : null,
                        'migrated_at' => now(),
                    ];

                    DB::table('athlete_enrollment_status_backups')->insert($backupData);
                } catch (\Exception $e) {
                    Log::error("Failed to insert backup record for enrollment {$enrollmentId}", [
                        'error' => $e->getMessage(),
                        'data' => [
                            'enrollment_id' => $enrollmentId,
                            'individual_id' => $individualId,
                            'event_id' => $eventId,
                            'discipline_id' => $disciplineId,
                        ],
                    ]);
                    // Re-throw to be caught by outer try-catch
                    throw $e;
                }

                // Update the enrollment status
                $enrollment->update([
                    'status_class' => $newStatus,
                ]);

                // Collect details for logging
                $details[] = [
                    'id' => $enrollmentId,
                    'event_id' => $eventId,
                    'individual_id' => $individualId,
                    'discipline_id' => $disciplineId,
                    'payment_status' => $paymentStatus,
                    'total_price' => $totalPrice,
                ];
            } catch (\Exception $exception) {
                // Log the error but continue processing other records
                Log::error("Failed to process enrollment ID {$enrollment->id}", [
                    'error' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                ]);
            }
        }

        // Enhanced logging with more details
        if ($count > 0) {
            Log::info("Updated {$count} athlete enrollments from pending_payment to discipline_assigned status", [
                'details' => $details,
                'backup_table' => 'athlete_enrollment_status_backups',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * We can now provide a reversal mechanism using the backup data.
     */
    public function down(): void
    {
        // Check if the backup table exists
        if (Schema::hasTable('athlete_enrollment_status_backups')) {
            // Get all the backups
            $backups = DB::table('athlete_enrollment_status_backups')->get();

            foreach ($backups as $backup) {
                try {
                    // Find the enrollment and revert its status
                    $enrollment = AthleteEnrollment::find($backup->enrollment_id);
                    if ($enrollment) {
                        $enrollment->update([
                            'status_class' => $backup->old_status,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to revert enrollment ID {$backup->enrollment_id}", [
                        'error' => $e->getMessage(),
                    ]);
                    // Continue with other records
                }
            }

            // Log the reversion
            Log::info('Reverted ' . $backups->count() . ' athlete enrollments to their original status');

            // Drop the backup table
            Schema::dropIfExists('athlete_enrollment_status_backups');
        }
    }
};
