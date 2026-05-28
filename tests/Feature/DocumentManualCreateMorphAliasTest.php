<?php

use App\Enums\UserGroupEnum;
use App\Http\Controllers\Admin\IndividualController as AdminIndividualController;
use App\Livewire\DocumentManualCreateComponent;
use App\Models\Group;
use App\Models\User;
use Domain\Documents\Models\Document;
use Domain\Documents\Models\DocumentType;
use Domain\Documents\States\PendingDocumentState;
use Domain\Individuals\Models\Individual;
use Domain\Payments\Models\PaymentMethod;
use Domain\Products\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('saveDocument stores morph alias for individual owner type', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $individual = Individual::factory()->create();
    Product::factory()->create();
    DocumentType::factory()->create(['code' => 'ORD']);
    PaymentMethod::factory()->create(['driver' => 'offline', 'is_enabled' => true]);

    Livewire::test(DocumentManualCreateComponent::class)
        ->set('customerType', 'individual')
        ->set('selectedIndividualId', $individual->id)
        ->set('documentDataArray', [
            'customer_name' => 'Test Customer',
            'tax_number' => '123456789',
            'due_date' => now()->addDays(30)->toDateString(),
            'notes' => '',
            'net_value' => 100,
            'tax_value' => 23,
            'total_value' => 123,
        ])
        ->set('documentDetailDataArray', [
            [
                'owner_id' => $individual->id,
                'owner_type' => 'individual',
                'description' => 'Test item',
                'quantity' => 1,
                'unit_value' => 100,
                'tax_percentage' => 23,
                'total_value' => 123,
                'customer_name' => 'Test Customer',
            ],
        ])
        ->call('saveDocument')
        ->assertRedirect(route('admin.document.index'));

    $document = Document::where('owner_id', $individual->id)->first();

    expect($document)->not->toBeNull()
        ->and($document->owner_type)->toBe('individual');
});

test('saveDocument stores morph alias for entity owner type', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $entity = \Domain\Entities\Models\Entity::factory()->create();
    Product::factory()->create();
    DocumentType::factory()->create(['code' => 'ORD']);
    PaymentMethod::factory()->create(['driver' => 'offline', 'is_enabled' => true]);

    Livewire::test(DocumentManualCreateComponent::class)
        ->set('customerType', 'entity')
        ->set('selectedEntityId', $entity->id)
        ->set('documentDataArray', [
            'customer_name' => 'Test Entity',
            'tax_number' => '987654321',
            'due_date' => now()->addDays(30)->toDateString(),
            'notes' => '',
            'net_value' => 200,
            'tax_value' => 46,
            'total_value' => 246,
        ])
        ->set('documentDetailDataArray', [
            [
                'owner_id' => $entity->id,
                'owner_type' => 'entity',
                'description' => 'Test entity item',
                'quantity' => 1,
                'unit_value' => 200,
                'tax_percentage' => 23,
                'total_value' => 246,
                'customer_name' => 'Test Entity',
            ],
        ])
        ->call('saveDocument')
        ->assertRedirect(route('admin.document.index'));

    $document = Document::where('owner_id', $entity->id)->first();

    expect($document)->not->toBeNull()
        ->and($document->owner_type)->toBe('entity');
});

test('migration normalizes full class names to morph aliases', function () {
    $individual = Individual::factory()->create();
    $user = User::factory()->create();
    $documentType = DocumentType::factory()->create();
    $paymentMethod = PaymentMethod::factory()->create(['driver' => 'offline']);

    $document = Document::forceCreate([
        'id' => \Illuminate\Support\Str::uuid()->toString(),
        'type_id' => $documentType->id,
        'owner_type' => 'Domain\\Individuals\\Models\\Individual',
        'owner_id' => $individual->id,
        'status_class' => 'draft',
        'customer_name' => 'Test',
        'tax_number' => '123',
        'net_value' => 100,
        'tax_value' => 23,
        'total_value' => 123,
        'method_id' => $paymentMethod->id,
        'due_date' => now(),
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    expect($document->getRawOriginal('owner_type'))->toBe('Domain\\Individuals\\Models\\Individual');

    $migration = require database_path('migrations/2026_04_13_122242_normalize_document_owner_type_morph_aliases.php');
    $migration->up();

    $document->refresh();
    expect($document->getRawOriginal('owner_type'))->toBe('individual');
});

test('renormalization migration normalizes full class names created after older backfills', function () {
    $individual = Individual::factory()->create();
    $user = User::factory()->create();
    $documentType = DocumentType::factory()->create();
    $paymentMethod = PaymentMethod::factory()->create(['driver' => 'offline']);

    $document = Document::forceCreate([
        'id' => \Illuminate\Support\Str::uuid()->toString(),
        'type_id' => $documentType->id,
        'owner_type' => Individual::class,
        'owner_id' => $individual->id,
        'status_class' => 'draft',
        'customer_name' => 'Test',
        'tax_number' => '123',
        'net_value' => 100,
        'tax_value' => 23,
        'total_value' => 123,
        'method_id' => $paymentMethod->id,
        'due_date' => now(),
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $migration = require database_path('migrations/2026_05_12_220000_renormalize_document_owner_type_morph_aliases.php');
    $migration->up();

    $document->refresh();
    expect($document->getRawOriginal('owner_type'))->toBe('individual');
});

test('admin individual profile loads alias and legacy owned payment documents', function () {
    Group::unguarded(fn () => Group::firstOrCreate(
        ['id' => UserGroupEnum::ADMIN->value],
        ['code' => UserGroupEnum::ADMIN->name, 'name' => 'Admin'],
    ));

    $this->actingAs(User::factory()->create([
        'group_id' => UserGroupEnum::ADMIN->value,
    ]));

    $individual = Individual::factory()->create();
    $documentType = DocumentType::factory()->create([
        'code' => 'ORD',
        'name' => 'Order',
        'prefix' => 'ORD',
    ]);

    $aliasDocument = Document::factory()->create([
        'type_id' => $documentType->id,
        'owner_id' => $individual->id,
        'owner_type' => 'individual',
        'status_class' => PendingDocumentState::class,
    ]);

    $legacyDocument = Document::factory()->create([
        'type_id' => $documentType->id,
        'owner_id' => $individual->id,
        'owner_type' => Individual::class,
        'status_class' => PendingDocumentState::class,
    ]);

    $view = app(AdminIndividualController::class)->show($individual->id);
    $documentIds = $view->getData()['payment_documents']->pluck('id')->all();

    expect($documentIds)
        ->toContain($aliasDocument->id)
        ->toContain($legacyDocument->id);
});
