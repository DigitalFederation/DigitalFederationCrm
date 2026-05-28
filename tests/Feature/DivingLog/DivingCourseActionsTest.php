<?php

use App\Models\User;
use Domain\Certifications\Models\Certification;
use Domain\DivingLogs\Actions\CreateDivingCourseAction;
use Domain\DivingLogs\Actions\DeleteDivingCourseAction;
use Domain\DivingLogs\Actions\UpdateDivingCourseAction;
use Domain\DivingLogs\DataTransferObject\DivingCourseData;
use Domain\DivingLogs\Models\DivingCourse;
use Domain\Entities\Models\Entity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

/**
 * @property User $user
 * @property Entity $entity
 * @property Certification $certification
 */

// Global setup for all tests in this file
beforeEach(function () {
    // Create the necessary role if it doesn't exist
    Role::findOrCreate('entity-admin', 'web');

    // Create an Entity
    $this->entity = Entity::factory()->create();

    // Create a User, assign the role, and associate with the Entity
    $this->user = User::factory()->create();
    $this->user->assignRole('entity-admin');
    $this->entity->users()->attach($this->user->id); // Assuming `users` relationship on Entity model

    // Create a Certification
    $this->certification = Certification::factory()->create();

    // Explicitly fetch the user model again to help linter type hinting
    /** @var User $testUser */
    $testUser = User::find($this->user->id);

    // Act as the created user for all tests
    actingAs($testUser);
});

// Test suite for CreateDivingCourseAction
test('CreateDivingCourseAction successfully creates a diving course', function () {
    // Arrange
    $action = app(CreateDivingCourseAction::class); // Resolve using service container
    $courseData = new DivingCourseData(
        entity_id: $this->entity->id,
        certification_id: $this->certification->id,
        start_date: now()->addMonth()->toDateString(),
        about: 'Test course description.'
    );

    // Act
    $createdCourse = $action($courseData);

    // Assert
    expect($createdCourse)->toBeInstanceOf(DivingCourse::class);
    expect($createdCourse->entity_id)->toBe($this->entity->id);
    expect($createdCourse->certification_id)->toBe($this->certification->id);
    expect($createdCourse->about)->toBe('Test course description.');
    expect($createdCourse->start_date)->toBeInstanceOf(\Carbon\Carbon::class); // Model casts to Carbon
    expect($createdCourse->start_date->toDateString())->toBe(now()->addMonth()->toDateString());

    assertDatabaseHas('entity_diving_courses', [
        'entity_id' => $this->entity->id,
        'certification_id' => $this->certification->id,
        'start_date' => now()->addMonth()->toDateString(),
        'about' => 'Test course description.',
    ]);
});

// Test suite for UpdateDivingCourseAction
test('UpdateDivingCourseAction successfully updates a diving course', function () {
    // Arrange: Create an initial course
    $existingCourse = DivingCourse::create([
        'entity_id' => $this->entity->id,
        'certification_id' => $this->certification->id,
        'start_date' => now()->addDays(10)->toDateString(),
        'about' => 'Initial description.',
    ]);

    $action = app(UpdateDivingCourseAction::class);
    $updatedData = new DivingCourseData(
        entity_id: $this->entity->id, // Usually shouldn't change entity owner
        certification_id: $this->certification->id, // Usually shouldn't change certification
        start_date: now()->addMonths(2)->toDateString(), // Updated start date
        about: 'Updated course description.' // Updated about text
    );

    // Act
    $result = $action($existingCourse, $updatedData);

    // Assert
    expect($result)->toBeTrue(); // Action returns boolean

    assertDatabaseHas('entity_diving_courses', [
        'id' => $existingCourse->id,
        'entity_id' => $this->entity->id,
        'certification_id' => $this->certification->id,
        'start_date' => now()->addMonths(2)->toDateString(),
        'about' => 'Updated course description.',
    ]);

    // Ensure original data is gone (optional but good)
    assertDatabaseMissing('entity_diving_courses', [
        'id' => $existingCourse->id,
        'start_date' => now()->addDays(10)->toDateString(),
        'about' => 'Initial description.',
    ]);
});

// Test suite for DeleteDivingCourseAction
test('DeleteDivingCourseAction successfully deletes a diving course', function () {
    // Arrange: Create a course to delete
    $courseToDelete = DivingCourse::create([
        'entity_id' => $this->entity->id,
        'certification_id' => $this->certification->id,
        'start_date' => now()->addDays(5)->toDateString(),
        'about' => 'Course to be deleted.',
    ]);

    assertDatabaseHas('entity_diving_courses', ['id' => $courseToDelete->id]);

    $action = app(DeleteDivingCourseAction::class);

    // Act
    $action($courseToDelete); // Action might return void or bool, we care about the side effect

    // Assert
    assertDatabaseMissing('entity_diving_courses', [
        'id' => $courseToDelete->id,
    ]);
});
