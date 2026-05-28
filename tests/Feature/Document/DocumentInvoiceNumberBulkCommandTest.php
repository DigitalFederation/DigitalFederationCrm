<?php

use Domain\Documents\Models\Document;
use Domain\Documents\States\PaidDocumentState;
use Domain\Documents\States\PendingDocumentState;

use function Pest\Laravel\artisan;

beforeEach(function () {});

it('ensures generate-missing-invoices command generates sequential invoice numbers', function () {
    // Create documents for multiple years, some with missing invoice numbers
    $yearStart = now()->year - 2; // Let's test over a span of three years

    foreach (range($yearStart, now()->year) as $year) {
        foreach (range(1, 5) as $index) {
            // Creating documents with alternating states and ensuring some have no invoice numbers
            $status = $index % 2 == 0 ? PaidDocumentState::class : PendingDocumentState::class;
            Document::factory()->create([
                'status_class' => $status,
                'total_value' => 100.00,
                'amount_paid' => $status === PaidDocumentState::class ? 100.00 : 0.00, // Assuming paid documents have 'amount_paid' equal to 'total_value'
                'invoice_number' => $index % 2 == 0 ? null : $index, // Assuming even-indexed docs are paid without invoice numbers
                'updated_at' => now()->setYear($year), // Setting documents to belong to different years
            ]);
        }
    }

    // Run the command
    artisan('documents:generate-missing-invoices')->assertSuccessful();

    // Now, let's verify the results
    foreach (range($yearStart, now()->year) as $year) {
        $documents = Document::whereYear('updated_at', $year)->orderBy('updated_at')->get();

        $lastInvoiceNumber = 0;
        foreach ($documents as $document) {
            if ($document->status_class === PaidDocumentState::class) {
                // Ensure that every paid document has an invoice number and it follows the sequence
                expect($document->invoice_number)->not->toBeNull()
                    ->and($document->invoice_number)->toBeGreaterThan($lastInvoiceNumber);

                $lastInvoiceNumber = $document->invoice_number;
            }
        }
    }
});
