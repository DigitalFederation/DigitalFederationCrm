# Events & Enrollments System

This document outlines the features of the event management system, including the registration process, visibility rules, enrollment credits, and the API.

---

## 1. Event Registration & Validation

The system manages a complex event registration process, particularly for scenarios where multiple organizations (e.g., a Federation and an Entity/Club) might register the same athlete.

### Registration Workflow

1.  **Initial Selection**: Organizations select participants to register for an event.
2.  **Discipline Assignment**: Organizations assign specific disciplines to their registered athletes through the enrollment process.

### Key Validation Rules

-   **Multi-Organization Registration**: The system **explicitly allows** a Federation and an Entity to register the same athlete for the same event. This provides flexibility for athletes participating under different banners.
-   **Discipline Uniqueness**: However, each organization must assign **different** disciplines to that athlete. The system prevents an athlete from being registered for the same discipline by more than one organization.
-   **Same-Type Blocking**: The system prevents an athlete from being registered by two different Federations or two different Entities for the same event.
-   **Self-Registration Lock**: If an athlete has self-registered, an organization cannot register them, and vice-versa.

### Technical Implementation

The `CheckExistingEventEnrollmentAction` is the core of this logic. Its behavior changes based on whether a discipline is specified:
-   **During initial registration (no discipline specified)**: It allows cross-organization registration.
-   **During discipline assignment**: It checks for discipline-specific conflicts.

---

## 2. Sport-Based Enrollment Restrictions (Entity Portal)

When enrolling participants through the Entity portal, the system enforces that individuals must be registered for the event's sport within that entity.

### Business Rule

- **Athletes**: Can only be enrolled if they are registered as athletes for that sport in the club (via `EntityAthlete` table)
- **Coaches**: Can only be enrolled if they are registered as coaches for that sport in the club (via `EntityProfessionalRole` table)
- **Team Officials**: No sport restriction - any active member of the entity can be enrolled

### How It Works

1. When an entity creates an event enrollment, the system checks:
   - The event's `Competition.sport_id` identifies the sport/modality
   - For athletes: Individual must have an active record in `entity_athletes` with matching `entity_id` and `sport_id`
   - For coaches: Individual must have an active record in `entity_professional_role` with matching `entity_id` and `sport_id`

2. This is enforced **when the competition has these requirements enabled** via the per-competition toggles `requires_athlete_entity_sport_registration` and `requires_coach_entity_sport_registration` (both default to `true`)

3. Federation-level enrollments are NOT affected by this rule

### Technical Implementation

- **Athletes**: `ApplyAthleteEligibilityFiltersAction::applyEntityAthleteRequirement()`
- **Coaches**: `GetEligibleCoachesAction` checks `professionalRoleEntities` relationship

### Related Tables

| Table | Purpose |
|-------|---------|
| `entity_athletes` | Tracks which athletes represent which sports in each entity |
| `entity_professional_role` | Tracks which coaches/professionals represent which sports in each entity |

---

## 3. Event Visibility Rules

Event visibility in the federation portal is governed by a strict set of rules to ensure federations only see relevant events.

An event is displayed only if **ALL** of the following conditions are met:

1.  **Visibility Flag**: `is_visible` must be `true`.
2.  **Status**: Must not be `Archived` or `Candidacy`.
3.  **Enrollment Type**: Must not be `only_individuals` or `only_entities` (as this view is for federations).
4.  **Dates**: By default, only shows upcoming events (`end_date` is in the future).
5.  **Geographical Coverage**: This is a common point of confusion.
    *   **International**: Always shown.
    *   **National**: Only shown if the event is explicitly linked to the user's federation's country.
    *   **Important**: An event intended for specific countries **must** have its `event_geographical_coverage` set to `National`. If it's left as `NULL` or `International` but has country links, it will be hidden from national filters.
6.  **User Filters**: The list is further refined by the user's selected category or search terms.

---

## 3. Enrollment Credit System

This feature provides flexibility for event registrations after payment has been made.

### How It Works

-   **Credit Generation**: When a **paid** participant is removed from an event, the system automatically generates a credit for the organization that registered them.
-   **Role-Specific**: Credits are specific to the participant's role (athlete, coach, referee, etc.). An athlete credit can only be used for a new athlete registration.
-   **Credit Usage**: When the organization registers a new participant for the **same event**, the system automatically checks for and applies any available credits for that role, reducing the payment amount accordingly.
-   **Limitations**: Credits are non-transferable between events or organizations and typically expire after the event ends.

---

## 4. Events API

The system provides a RESTful API for accessing event data.

-   **Endpoint**: `GET /api/events/competitions`
-   **Authentication**: Requires a Bearer token in the `Authorization` header.
-   **Rate Limiting**: 20 requests per minute per API token.

### Query Parameters

-   `start_date` (YYYY-MM-DD) and `end_date` (YYYY-MM-DD): Must be supplied **together** to filter by date. The filter returns events whose date range **overlaps** the requested range (events starting or ending within it, plus events that fully span it). If only one of the two is provided, no date filtering is applied. `end_date` must be on or after `start_date`.
-   `competition_type` (string): Filter by type (e.g., 'world_championship', 'continental_championship').

### Response

The API returns a JSON object containing an array of event data, including details about the event, venue, and associated competition.

---

## 5. Event Applications (Candidaturas) Module

Separate module for managing event applications/candidatures from entities.

### Application Types

1. **Federation-Initiated**: Admin creates template, entities submit applications
2. **Direct Submissions**: Entities create event proposals directly

### Critical Business Rule

**Each club/association can only submit ONE application per event/template**, but multiple different clubs can submit applications for the same event.

### State Machine

Applications follow this workflow:

```
DraftApplicationState → SubmittedApplicationState → InValidationApplicationState
    ↓                                                        ↓
    └─ (entity editing)                            ReturnedForCorrectionApplicationState
                                                             ↓
                                               ApprovedApplicationState / RejectedApplicationState
                                                             ↓
                                               PublishedApplicationState (optional)
```

### Key Actions

- `CreateApplicationAction` - Create new application
- `SubmitApplicationAction` - Submit draft to federation
- `ValidateApplicationAction` - Admin starts validation
- `ReturnForCorrectionAction` - Admin requests corrections
- `ApproveApplicationAction` / `RejectApplicationAction` - Admin decision
- `CheckDuplicateApplicationAction` - Validate entity hasn't already applied

### Domain Location

`src/Domain/EventApplications/`
