# Entity Namespace: Roles and Permissions

This document outlines the roles available for the `Entity` namespace, their permissions, and how they are triggered within the system. This information is derived from the `RoleAndPermissionSeeder` and its usage throughout the application.

## Summary of Entity Roles

The `Entity` namespace has a set of roles that grant specific permissions to entities (e.g., clubs, schools, companies). These roles are assigned based on the entity's type and its relationship with the federation.

### Core Entity Roles

-   `entity-admin`: The primary administrative role for an entity. Grants broad access to manage the entity's members, events, and orders.
-   `entity-company`: A role for company-type entities, providing access to orders.
-   `entity-sport-admin`: For sport-focused entities. Grants access to sport-specific menus, attachments, and events.
-   `entity-diving-admin`: For diving-focused entities. Grants access to diving-specific menus, attachments, and events.
-   `entity-cmas-operator`: A special role for entities that are authorized to handle international licenses and certifications (CMAS/DIVING committee content).

> **Note:** The `entity-scientific-admin` role was merged into other roles during the role simplification migration.

## Triggering Role Assignment

Unlike the `Individual` namespace, which relies heavily on the `SyncUserRolesAction`, the `Entity` roles are typically assigned during the entity's creation or when its relationship with a user is established. The `SyncUserEntityCommitteeAction` also plays a role in this process.

### `SyncUserEntityCommitteeAction`

This action is responsible for synchronizing the committee-based roles (`entity-sport-admin`, `entity-diving-admin`, `entity-scientific-admin`) with the entity's designated committee type. It also ensures that the `entity-admin` role is present.

### Key Triggers for Role Assignment

-   **Entity Creation**: When a new entity is created, the `entity-admin` role is assigned to the user who created it. Depending on the entity's type (sport, diving, etc.), the corresponding committee-based admin role is also assigned.
    -   *Files*: `app/Http/Controllers/EntityController.php`, `app/Http/Controllers/Cmas/EntityController.php`, `app/Http/Controllers/Federation/EntityController.php`

-   **International Certifications and Licenses**: The `entity-cmas-operator` role is checked when an entity attempts to purchase or manage international certifications or licenses. This role is a prerequisite for these actions.
    -   *Files*: `app/Http/Controllers/Entity/LicenseAttributedController.php`, `src/Domain/Certifications/Actions/PurchaseCertificationAction.php`, `src/Domain/Certifications/Models/Certification.php`

-   **Diving-Specific Features**: The `entity-diving-admin` role is frequently checked for access to diving-related features, such as managing diving courses.
    -   *Files*: `app/Http/Controllers/Entity/EntityController.php`, `app/Http/Controllers/Entity/DivingCourseController.php`

-   **Policy Enforcement**: The `DivingCoursePolicy` explicitly checks for the `entity-admin` role to authorize actions like creating, updating, and deleting diving courses.
    -   *File*: `app/Policies/DivingCoursePolicy.php`

-   **UI Rendering**: The views use Blade's `@role` and `@hasanyrole` directives to conditionally display UI elements based on the entity's roles. For example, certain management features are only visible to users with the `entity-admin` or `entity-diving-admin` roles.
    -   *File*: `resources/views/web/entity/profile/edit.blade.php`
