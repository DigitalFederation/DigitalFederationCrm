<?php

namespace App\Console\Commands;

use Domain\Documents\Actions\GenerateDocumentInvoiceNumberAction;
use Domain\Documents\Models\Document;
use Domain\Documents\States\PaidDocumentState;
use Domain\Payments\Models\PaymentMethod;
use Domain\Payments\Models\PaymentTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateMissingInvoiceNumbers extends Command
{
    protected $signature = 'documents:generate-missing-invoices';
    protected $description = 'Generate invoice numbers for paid documents missing them';

    public function handle()
    {
        $this->info('Checking for paid documents without invoice numbers...');

        // Order by 'updated_at' to approximate the payment date
        $documents = Document::where('status_class', PaidDocumentState::class)
            ->whereNull('invoice_number')
            ->orderBy('updated_at')
            ->get();

        if ($documents->isEmpty()) {
            $this->info('No documents require updating.');

            return 0;
        }

        $generateInvoiceNumber = new GenerateDocumentInvoiceNumberAction;

        $offlinePaymentMethod = PaymentMethod::where('handler', 'OfflinePaymentHandler')->first();

        foreach ($documents as $document) {
            DB::beginTransaction();
            try {
                $generateInvoiceNumber($document);
                $this->info("Generated invoice number for document ID: {$document->id}");

                // If there's no existing payment, create one
                if ($document->amount_paid < $document->total_value) {
                    $paymentTransaction = new PaymentTransaction([
                        'document_id' => $document->id,
                        'payment_method_id' => $offlinePaymentMethod->id,
                        'amount' => $document->total_value,
                        'status' => 'success',
                        'payment_data' => json_encode(['comment' => 'Bulk update missing invoice numbers logic']),
                    ]);
                    $paymentTransaction->save();

                    $document->amount_paid = $document->total_value;
                    $document->save();

                    $this->info("Payment recorded for document ID: {$document->id}");
                }

                // Log the change
                Log::info('Updated document invoice number', [
                    'document_id' => $document->id,
                    'new_invoice_number' => $document->invoice_number,
                ]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to update document and create payment transaction', [
                    'document_id' => $document->id,
                    'error' => $e->getMessage(),
                ]);
                $this->error("Failed to process document ID: {$document->id}");
            }
        }

        $this->info('Completed updating documents.');

        return 0;
    }
}
