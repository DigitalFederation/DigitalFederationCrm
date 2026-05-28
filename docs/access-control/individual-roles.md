# Individual Namespace: Roles and Permissions

This document outlines the roles available for the `Individual` namespace, their permissions, and how they are triggered within the system. This information is derived from the `RoleAndPermissionSeeder` and its usage throughout the application.

## Summary of Individual Roles

The `Individual` namespace has a set of roles that grant specific permissions to users. These roles are assigned based on the individual's professional roles and qualifications.

### Core Individual Roles

-   `individual-approved`: A foundational role for an approved individual. Grants access to the starter menu, orders, licenses, and official documents.
-   `individual-coach`: For coaches. Grants access to the coach menu, events, and sport-specific licenses and documents.
-   `individual-instructor`: For instructors. Grants access to the instructor menu, events, and diving-related licenses and documents.
-   `individual-athlete`: For athletes. Grants access to the athlete menu, events, and sport-specific licenses and documents.
-   `individual-technical-official`: For technical officials (judges and referees). Grants access to both judge and referee menus, events, and sport-specific licenses and documents.
-   `individual-leader`: For leaders. Grants access to the leader menu, events, and diving-related licenses and documents.
-   `individual-diver`: For divers. Grants access to the diver menu, events, diving log, and diving-related licenses and documents.
-   `individual-scientific`: For scientific divers. Grants access to the scientific menu, events, and related licenses and documents.
-   `individual-sport`: For sport-focused individuals. Grants access to the sport menu, events, and related licenses and documents.
-   `individual-lms-instructor`: For LMS instructors. Grants access to the LMS menu and student progression management.

### View Roles

-   `view-individual-coach`: Grants view access to the coach and sport menus.
-   `view-individual-technical-official`: Grants view access to the judge, referee, and sport menus.
-   `view-individual-diving-instructor`: Grants view access to the diver and instructor menus.
-   `view-individual-scientific-instructor`: Grants view access to the scientific and instructor menus.
-   `view-individual-diving-leader`: Grants view access to the diver and leader menus.
-   `view-individual-scientific-leader`: Grants view access to the scientific and leader menus.

## Triggering Role Assignment

The primary mechanism for assigning these roles is the `SyncUserRolesAction`. This action is responsible for synchronizing a user's roles based on their professional roles within the system.

### `SyncUserRolesAction` Logic

The `SyncUserRolesAction` maps professional role names (e.g., 'instructor', 'coach') to the corresponding `individual-` roles defined in the `RoleAndPermissionSeeder`. When an individual's professional status changes (e.g., they become a certified instructor), this action is triggered to update their permissions accordingly.

For example, if an individual is assigned the professional role of 'instructor', the `SyncUserRolesAction` will grant them the `individual-instructor` role, which in turn provides them with the permissions to access the instructor menu, manage events, and handle diving-related licenses.

### Example from the Code

The following snippet from `src/Domain/Users/Actions/SyncUserRolesAction.php` illustrates the mapping:

```php
[
    // ...
    'instructor' => 'individual-instructor',
    'coach' => 'individual-coach',
    'athlete' => 'individual-athlete',
    // ...
]
```

This ensures that the user's permissions are always in sync with their current professional standing in the federation.

## Where `SyncUserRolesAction` is Triggered

The `SyncUserRolesAction` is a critical component for keeping user permissions up-to-date. It is triggered in several key places throughout the application, ensuring that role changes are reflected in response to various events:

-   **Manual Trigger**: An artisan command `sync:user-roles` exists to manually trigger the synchronization for all users. This is useful for correcting any inconsistencies or for system-wide updates.
    -   *File*: `app/Console/Commands/SyncUserRoles.php`

-   **Individual Creation**: When a new individual is created in the system, their roles are synchronized.
    -   *File*: `src/Domain/Individuals/Actions/CreateIndividualAction.php`

-   **Federation Membership**: Roles are updated when an individual's relationship with a federation changes:
    -   When an individual joins a federation.
        -   *File*: `app/Http/Controllers/Individual/FederationController.php`
    -   When a federation administrator approves an individual's request to join.
        -   *File*: `app/Http/Controllers/Federation/IndividualController.php`
    -   When a federation administrator approves or rejects an individual's request.
        -   *File*: `app/Http/Controllers/Federation/IndividualRequestController.php`

-   **License Status Changes**: An individual's roles are updated when their licenses are activated or expire.
    -   Activation: `src/Domain/Licenses/Actions/ActivateLicenseAttributedAction.php`
    -   Expiration: `src/Domain/Licenses/Actions/ExpireLicenseAttributedAction.php`
    -   A dedicated action `SyncUserRolesBasedOnLicenseAction` also exists for this purpose.

-   **Certification Status Changes**: Similarly, roles are updated when an individual's certifications are activated or expire.
    -   Activation: `src/Domain/Certifications/Actions/ActivateCertificationAttributedAction.php`
    -   Expiration: `src/Domain/Certifications/Actions/ExpireCertificationAttributedAction.php`

-   **Asynchronous Updates**: The `SyncUserRolesJob` allows for role synchronization to be performed in the background, ensuring that the user interface remains responsive during potentially long-running updates.
    -   *File*: `app/Jobs/SyncUserRolesJob.php`
