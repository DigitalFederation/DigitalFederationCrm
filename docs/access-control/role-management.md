---
title: Role Management
description: Dynamic role management system for administrators
---

# Role Management System

## Overview

The Dynamic Role Management System enables administrators to create, modify, and manage user roles and permissions. It includes built-in safeguards to prevent system lockouts and unauthorized access.

---

## Role Hierarchy

### CMAS (Root Organization)
- **cmas-super-admin** - Full system access
- **cmas-sport-admin** - Sports-specific administration
- **cmas-diving-admin** - Diving-specific administration
- **cmas-scientific-admin** - Scientific-specific administration

### Federations
- **federation-admin** - Federation management
- **federation-sport-admin** - Federation sports management
- **federation-diving-admin** - Federation diving management

### Local Federations
- **local-federation-admin** - Local federation management
- Similar sport/diving/scientific variants

### Entities (Clubs/Schools/Centers)
- **entity-admin** - Entity management
- **entity-sport-admin** - Entity sports management
- **entity-diving-admin** - Entity diving management

### Individuals
- **individual-approved** - Basic approved individual
- **individual-coach** - Coach role
- **individual-instructor** - Instructor role
- **individual-athlete** - Athlete role
- Various specialized roles (judges, referees, divers, etc.)

---

## Core Features

### Role Management
- Create, edit, and delete roles
- Role templates for common role types
- Role duplication for quick setup
- Bulk operations for multiple roles

### Permission Management
- Custom permission creation
- Permission groups by functional area
- Route-permission mapping
- Permission inheritance through role hierarchy

### Security Features

**Protected Roles:**
- System roles cannot be deleted
- Critical admin roles are protected
- Minimum admin count enforced

**Audit Trail:**
- All changes logged with user attribution
- Before/after comparison
- Exportable audit reports

---

## Permission Categories

| Category | Examples |
|----------|----------|
| User Management | access users, create user, edit user, delete user |
| Federation Management | access federations, create federation, manage membership |
| Entity Management | access entities, create entities, manage membership |
| License Management | access licenses, create license, edit license |
| Certification Management | access certifications, manage certifications |
| Document Management | access documents, download reports, generate documents |
| System Administration | access settings, manage configuration, view logs |

---

## Security Rules

1. **Hierarchical Access**: Lower-level admins cannot modify higher-level roles
2. **Scope Limitations**: Federation admins can only modify federation-level roles
3. **Protected Roles**: System-critical roles cannot be deleted
4. **Minimum Admins**: System ensures at least one super-admin always exists
5. **Confirmation Required**: Dangerous operations require multi-step confirmation
