# Miscellaneous Features

This document covers various other features of the Digital Federation CRM platform, including the individual import system, member numbering, accreditation badges, and the zones module.

---

## 1. Individual Import System

This feature allows federation administrators to perform a bulk import of individuals from CSV/XLS files.

### Key Features

-   **File Upload**: Supports CSV, XLS, and XLSX formats up to 10MB.
-   **Field Mapping**: A flexible UI allows administrators to map columns from their source file (e.g., "First Name", "Email Address") to the corresponding platform fields (e.g., `name`, `email`).
-   **Validation & Preview**: Before the actual import, the system performs a comprehensive validation of the data. It provides a preview showing:
    *   The number of valid records.
    *   Warnings (e.g., potential duplicates).
    *   Errors (e.g., missing required fields, invalid email formats).
    *   A downloadable error report is generated for easy correction.
-   **Duplicate Resolution**: The system can detect duplicates based on a combination of name, surname, birthdate, and country. Administrators can choose a resolution strategy (e.g., Skip, Update).
-   **Queued Processing**: For large files, the import is handled asynchronously in the background, with real-time progress tracking visible in the UI.

---

## 2. Member Number System

This system provides automatic assignment of unique, sequential member numbers to individuals and entities upon their approval.

### How It Works

-   **Automatic Assignment**: Numbers are assigned when an individual or entity's relationship with a federation becomes active (e.g., `ActiveIndividualFederationState`).
-   **Separate Counters**: Individuals and entities have independent, sequential counters.
-   **Database**: Member numbers are stored in the `member_number` field on the `individuals` and `entities` tables. The next available number is stored in the `member_number_settings` table.
-   **Admin Control**: A dedicated settings page (`/admin/member-number-settings`) allows administrators to view and manually adjust the current counter values.
-   **Concurrency Safe**: The system uses database transactions with row locking to prevent race conditions and ensure number uniqueness.

---

## 3. Accreditation Badges

This feature streamlines the creation and management of accreditation badges for events.

### Core Components

-   **Profile Picture Requirement**: Event registration can be configured to require a profile picture for different participant categories (athlete, coach, etc.).
-   **Role Management**: A configurable list of accreditation roles (e.g., "Athlete", "Press", "LOC") can be created with specific background colors and permissions.
-   **Profile Picture Management**: An admin interface allows for reviewing, validating, and requesting re-uploads of profile pictures.
-   **Badge Design System**: A drag-and-drop interface for designing badge templates (A6 format). Dynamic elements include:
    *   Name, Club, Country, Flag
    *   QR Code (linking to the individual's public profile)
    *   Role Banner (with configured colors)
    *   Elements can be conditionally displayed (e.g., hide country for LOC members).
-   **PDF Generation**: Asynchronous, batch generation of high-quality PDFs for printing, with filtering options.

---

## 4. Zones & Districts Module

This module provides a more flexible way to organize geographic areas.

### Geographic Hierarchy

-   **District**: A new geographic subdivision that sits below the `Country` level.
-   **Zone**: A custom grouping that can contain multiple `Districts`, even from different countries.

This allows for creating custom regions (Zones) that don't necessarily align with the standard continental/national hierarchy, offering greater flexibility for organizing events and reporting.

### Relationships

-   **Districts** belong to one `Country` but can belong to many `Zones`.
-   **Entities**, **Federations**, and **Individuals** can be associated with multiple `Zones` and a single `District`.
-   The system includes scopes for easy filtering by Zone or District (e.g., `Entity::filterByZone($zoneId)`).

---

## 5. Diving Map & Available Courses (Example Deployment)

This feature is part of the diving example deployment and enhances its public-facing Diving Map.

### Functionality

-   **Entity-Managed Courses**: Entities (diving centers) can now manage a list of the diving courses they offer.
-   **Course Details**: Each course is linked to a `Certification` and can have a custom description and an optional start date.
-   **Extended Certification Data**: The `Certification` model was extended to include fields like `minimum_age` and the number of required water/theoretical sessions.
-   **Public Display**: The public Diving Map now displays this list of available courses on each entity's detail page, providing valuable information to potential customers.

---

## 6. Operations Center

Admin dashboard for managing background jobs, scheduled tasks, and system commands.

### Features

| Feature | Description |
|---------|-------------|
| **Queue Monitor** | Real-time view of pending, processing, and failed jobs. Retry/delete failed jobs. |
| **Scheduler Dashboard** | List of scheduled tasks with cron expressions, manual trigger capability |
| **Command Center** | Curated whitelist of safe commands, rate-limited execution (30s) |
| **Batch Monitor** | Active batch progress tracking with visual progress bars |
| **System Health** | Database, queue, storage health checks |

### Key Components

- `OperationsCenterService` - Queue stats, batch ops, command execution
- `SchedulerService` - Cron parsing, task execution
- Livewire components in `app/Livewire/Admin/OperationsCenter/`

### Access

- **Permission**: `access settings`
- **Location**: Admin Sidebar > Settings > Operations Center
- **Routes**: `/admin/operations/*`

### Command Whitelist Categories

- License Management (`licenses:activate-paid`, `command:ExpireLicenses`)
- Membership Management (`memberships:cancel-expiration`)
- Role Synchronization (`sync:all-user-roles`, etc.)
- QR Code Generation
- Data Maintenance
- Cache Management (`optimize:clear`)
