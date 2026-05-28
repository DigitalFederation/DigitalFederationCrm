<?php

use Domain\Payments\Models\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, ensure we have offline and EasyPay payment methods
        $this->ensureRequiredPaymentMethods();

        // Find SystemPay payment methods that need to be replaced
        $systemPayMethods = PaymentMethod::whereIn('driver', ['systempay', 'SystemPay'])->get();

        if ($systemPayMethods->isNotEmpty()) {
            // Get offline payment method as fallback
            $offlineMethod = PaymentMethod::where('driver', 'offline')->first();

            if ($offlineMethod) {
                // Update documents to use offline payment method instead
                foreach ($systemPayMethods as $systemPayMethod) {
                    DB::table('document')
                        ->where('method_id', $systemPayMethod->id)
                        ->update(['method_id' => $offlineMethod->id]);

                    // Update payment transactions if they exist
                    DB::table('payment_transactions')
                        ->where('payment_method_id', $systemPayMethod->id)
                        ->update(['payment_method_id' => $offlineMethod->id]);
                }

                // Now safely delete SystemPay payment methods
                PaymentMethod::whereIn('driver', ['systempay', 'SystemPay'])->delete();
            }
        }

        // Update any references to old handler class names (without full namespace)
        PaymentMethod::where('handler', 'SystemPayPaymentHandler')
            ->update([
                'handler' => 'Domain\Payments\Handlers\OfflinePaymentHandler',
                'driver' => 'offline',
            ]);

        PaymentMethod::where('handler', 'OfflinePaymentHandler')
            ->update(['handler' => 'Domain\Payments\Handlers\OfflinePaymentHandler']);

        PaymentMethod::where('handler', 'EasyPayPaymentHandler')
            ->update(['handler' => 'Domain\Payments\Handlers\EasyPayPaymentHandler']);
    }

    /**
     * Ensure required payment methods exist
     */
    private function ensureRequiredPaymentMethods(): void
    {
        // Ensure offline payment method exists
        if (! PaymentMethod::where('driver', 'offline')->exists()) {
            PaymentMethod::create([
                'name' => 'Bank Transfer',
                'driver' => 'offline',
                'instructions' => 'Please follow the payment instructions provided by your administrator.',
                'handler' => 'Domain\Payments\Handlers\OfflinePaymentHandler',
                'is_enabled' => true,
            ]);
        }

        // Ensure EasyPay payment method exists
        if (! PaymentMethod::where('driver', 'easypay')->exists()) {
            PaymentMethod::create([
                'name' => 'EasyPay',
                'driver' => 'easypay',
                'instructions' => 'Secure online payment with credit card, Multibanco, MBWay, and other methods',
                'handler' => 'Domain\Payments\Handlers\EasyPayPaymentHandler',
                'is_enabled' => true,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible as we're removing legacy code
        // and updating foreign key references
    }
};
