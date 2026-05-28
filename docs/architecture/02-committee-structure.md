---
title: Committee Structure
description: Committee types, internationality flags, and federation access control
---

# Committee Structure and Federation Access Control

> **Last Updated:** January 2026 (Commit 1730743)
> **Status:** Canonical reference for committee-based access control

## Overview

Certifications and licenses in the federation portal are organized by **committees**. The `is_international` flag lives **exclusively on the `committee` table** - not on individual certifications or licenses.

This architecture was implemented to:
1. Centralize internationality logic in one place
2. Enable proper federation-based access control via `federation_committee` pivot
3. Support both CMAS (international) and national diving services

## Committees Reference Table

| Code | Name | `is_international` | Description |
|------|------|-------------------|-------------|
| `SPORT` | Sport Committee | `false` | National underwater sports (swimming, finswimming, etc.) |
| `DIVINGSERVICES` | Diving Services Committee | `false` | National diving services (non-CMAS diving operations) |
| `DIVING` | CMAS Diving Committee | `true` | International CMAS diving certifications/licenses |
| `SCIENTIFIC` | CMAS Scientific Committee | `true` | International CMAS scientific diving |

## Federation Access by Type

### Main Federation (`is_default_federation = true`)

The main federation has access to ALL committees:

| Product Type | Committee |
|--------------|-----------|
| Sport | `SPORT` |
| Serviços de Mergulho | `DIVINGSERVICES` |
| Diving CMAS | `DIVING` |
| Scientific CMAS | `SCIENTIFIC` |

### International Diving Federation (Modalidade Federation)

The international diving federation only manages international content:

| Product Type | Committee |
|--------------|-----------|
| Diving CMAS | `DIVING` |
| Scientific CMAS | `SCIENTIFIC` |

### Associações Territoriais (`is_local = true`)

Local/territorial federations only manage national content:

| Product Type | Committee |
|--------------|-----------|
| Sport | `SPORT` |
| Serviços de Mergulho | `DIVINGSERVICES` |

## Code Examples

### Checking if a License/Certification is International

```php
// CORRECT - Use committee's is_international flag
$isInternational = $license->committee->is_international;
$isInternational = $license->committee->isInternational();
$isInternational = $license->isInternationalLicense();

// CORRECT - For certifications
$isInternational = $certification->committee->is_international;
$isInternational = $certification->isInternationalCertification();
```

### Filtering for Diving Licenses (Both Types)

```php
// CORRECT - Include both DIVING and DIVINGSERVICES
$isDivingLicense = $license->committee
    && in_array($license->committee->code, ['DIVING', 'DIVINGSERVICES']);

// In queries
->whereHas('license.committee', function ($q) {
    $q->whereIn('code', ['DIVING', 'DIVINGSERVICES']);
});
```

### Filtering by Internationality

```php
// International only (CMAS content)
->whereHas('license.committee', fn ($q) => $q->where('is_international', true));

// National only (non-CMAS content)
->whereHas('license.committee', fn ($q) => $q->where('is_international', false));

// Using scopes
Committee::international()->get(); // DIVING, SCIENTIFIC
Committee::national()->get();      // SPORT, DIVINGSERVICES
```

### Federation Committee Access Control

```php
// Check if federation can manage a committee
$federation->canManageCommittee($committee);
$federation->committees()->where('code', 'DIVING')->exists();

// Get federation's allowed committees
$federation->committees; // Collection of Committee models
```

## Key Files

| File | Purpose |
|------|---------|
| `app/Models/Committee.php` | Committee model with `isInternational()` helper and scopes |
| `src/Domain/Federations/Models/Federation.php` | Federation model with `canManageCommittee()` method |
| `app/Http/Middleware/EnsureFederationCanManageCommittee.php` | Middleware for committee-based authorization |
| `src/Domain/Licenses/Scopes/ExcludeInternationalScope.php` | Global scope that filters by `committee.is_international` |
| `database/migrations/2026_01_07_170502_*.php` | Migration that set up DIVINGSERVICES and international flags |

## Database Schema

### `committee` Table

```sql
CREATE TABLE committee (
    id BIGINT PRIMARY KEY,
    code VARCHAR(255) UNIQUE,      -- 'SPORT', 'DIVING', 'DIVINGSERVICES', 'SCIENTIFIC'
    name VARCHAR(255),
    is_international BOOLEAN       -- true for DIVING, SCIENTIFIC; false for SPORT, DIVINGSERVICES
);
```

### `federation_committee` Pivot Table

```sql
CREATE TABLE federation_committee (
    federation_id BIGINT REFERENCES federation(id),
    committee_id BIGINT REFERENCES committee(id),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    PRIMARY KEY (federation_id, committee_id)
);
```

## Technical Director Approval Flow

For **entity diving licenses** (both `DIVING` and `DIVINGSERVICES` committees), the purchase flow includes Technical Director (TD) approval:

```
Entity purchases diving license
    ↓
State: PendingTechnicalDirectorApprovalLicenseAttributedState
    ↓
TD approves
    ↓
State: PendingValidationLicenseAttributedState (Pending Federation)
    ↓
Federation validates
    ↓
State: ActiveLicenseAttributedState
```

See `src/Domain/Licenses/Actions/PurchaseLicenseAction.php` for implementation.

## Common Mistakes to Avoid

1. **Never check `is_international` on license or certification directly** - it no longer exists there
2. **Never filter diving licenses by only `'DIVING'`** - always include `['DIVING', 'DIVINGSERVICES']`
3. **Don't assume committee access** - always verify via `federation_committee` pivot or middleware
