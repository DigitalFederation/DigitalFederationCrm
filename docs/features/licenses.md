---
title: Licenses
description: License purchase, validation flow, TD approval, and state machine
---

# License System

## Overview

The license system enables entities and individuals to request and purchase licenses directly, with simplified state management and comprehensive federation oversight.

---

## 1. License States

Licenses go through the following states:

| State | Description |
|-------|-------------|
| **Pending TD Approval** | Diving license (entity only) awaiting Technical Director approval |
| **Pending Validation** | License awaiting admin/federation validation |
| **Pending** | License approved, awaiting payment |
| **Active** | Payment confirmed, license is valid |
| **Suspended** | Temporarily disabled by administrator |
| **Expired** | License has passed its expiration date |
| **Canceled** | License was rejected or canceled |

---

## 2. Initial State Rules

When a license is purchased, the initial state depends on:

| Purchaser | License Type | Requires Validation | Free | Initial State |
|-----------|--------------|---------------------|------|---------------|
| Entity | Diving | Yes | - | Pending TD Approval |
| Individual | Diving | Yes | - | Pending Validation |
| Any | Non-Diving | Yes | - | Pending Validation |
| Any | Any | No | Yes | Active |
| Any | Any | No | No | Pending (awaiting payment) |

**Important:** TD approval only applies to entities purchasing diving licenses. Individuals go directly to federation validation.

---

## 3. License Validity

### Configuration Options

- **Interval**: Duration number (e.g., 1, 2, 3)
- **Interval Unit**: weeks, months, or years
- **Validity Type**: fixed_duration or calendar_year

### Validity Rules

**Perpetual License:**
- No interval defined
- License never expires

**Fixed-Duration License:**
- Valid for exact interval from activation date
- Example: 1-year license activated March 15, 2025 → expires March 15, 2026

**Calendar Year License:**
- Expires on December 31st of the target year
- Example: 1-year license activated March 15, 2025 → expires December 31, 2025

---

## 4. Requirements

### Document Requirements

Licenses can require official documents before purchase:
- Documents must be in "Active" state
- Documents must not be expired
- Purchase is blocked if requirements not met

### Certification Requirements

Licenses can require certifications before purchase:
- Only applies to individual purchasers (entities exempt)
- All required certifications must be held
- Clear error message if requirements not met

---

## 5. Diving License Workflow

Diving licenses have a special validation workflow for entities.

### Entity vs Individual

| Purchaser | Flow |
|-----------|------|
| **Entity** | Request → TD Approval → Federation Validation → Payment → Active |
| **Individual** | Request → Federation Validation → Payment → Active |

### Entity Diving License Steps

1. Entity requests diving license
2. **TD Approval**: All assigned technical directors must approve
3. **Federation Validation**: Admin reviews and approves/rejects
4. **Payment**: If approved and paid license, awaits payment
5. **Active**: Payment confirmed, license is valid

### Validation Rules

- **TD Approval**: All assigned TDs must approve before federation review
- **Admin Approval**: Optional notes (max 500 characters)
- **Admin Rejection**: Reason required (max 500 characters), visible to entity

---

## 6. Purchase Types

### Direct Purchase
Individual or entity purchases license for themselves.

### Group Purchase
Entity purchases licenses for multiple members at once.

- Tracks which entity requested the license
- Members receive individual licenses
- Entity can manage all purchased licenses
