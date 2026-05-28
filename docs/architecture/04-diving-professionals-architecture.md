---
title: Diving Professionals Architecture
description: Architecture for diving professionals and technical directors
---

# Diving Professionals Architecture

## Overview

The Diving module manages two distinct types of professional relationships:

1. **Diving Professionals** - Instructors and other diving professionals who work with entities
2. **Technical Directors** - License-specific directors nominated during entity license requests

---

## 1. Diving Professionals (Instructors)

### Purpose
Allow entities to associate with certified diving professionals for operational activities.

### Business Rules

**Eligibility:**
- Individual must have at least one active diving professional certification
- Certifications can be from: CMAS, SSI, PADI, SDI/TDI, DDI, GUE
- Individual must have active diving license from federation

**Invitation Process:**
- Entity can invite any eligible professional
- Professional must accept invitation to activate relationship
- Both parties can deactivate the relationship
- Deactivation requires reason tracking

**Automatic Relationships:**
- When professional accepts invitation, they automatically become an entity member
- Professional can leave the entity at any time

### Workflow

1. Entity searches for professional by CMAS code
2. System checks eligibility (active certification)
3. Entity sends invitation
4. Professional receives notification
5. Professional accepts or rejects
6. If accepted: relationship activated, entity membership created

---

## 2. Technical Directors

### Purpose
Fulfill legal requirements for entity diving licenses. This is NOT a professional role - it's a license requirement.

### Key Differences from Diving Professionals

| Aspect | Diving Professional | Technical Director |
|--------|--------------------|--------------------|
| Invitation | Direct from entity | Only during license request |
| Relationship | Many-to-many | One per license |
| Purpose | Operational | Legal requirement |
| Can be removed | Yes, by either party | No, tied to license |

### Business Rules

- Directors can only be nominated during license request workflow
- Director must have certifications for the specified systems
- Director must accept nomination before license can be approved
- One director per license
- Cannot be removed once license is active

### Workflow

1. Entity requests diving license (School, Center, Equipment Rental, Gas Station)
2. Entity nominates technical director during request
3. System verifies director has required certifications
4. Director receives nomination notification
5. Director accepts or rejects
6. If rejected: entity must nominate another
7. If accepted: admin reviews and approves license
8. Director officially manages that specific license

---

## 3. Common Misconceptions

| Wrong | Correct |
|-------|---------|
| Technical Directors are just another professional role | Technical Directors are license-specific positions |
| Technical Directors can be invited directly | They can only be nominated during license requests |
| All diving professionals are technical directors | They are completely separate concepts |

**Note:** An individual CAN be both a diving professional AND a technical director - they serve different purposes.

---

## Glossary

| Term | Definition |
|------|------------|
| **Diving Professional** | Certified instructor who works with entities |
| **Technical Director** | Individual responsible for a specific entity diving license |
| **Entity** | Organization (diving center, school, etc.) |
| **CMAS Code** | Unique identifier for individuals in the federation |
| **Certification System** | Diving organization (PADI, SSI, CMAS, etc.) |
