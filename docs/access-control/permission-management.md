---
title: Permission Management
description: Dynamic permission management system for administrators
---

# Permission Management System

## Overview

The Dynamic Permission Management System allows administrators to create, manage, and assign permissions through the UI without modifying code.

---

## Key Features

### Permission Management
- Create, edit, and delete permissions
- Organize permissions by categories
- Bulk operations and import/export
- Protected system permissions that cannot be deleted

### Route-Permission Mapping
- Map permissions to routes dynamically
- Route scanner to identify unprotected routes
- Coverage statistics for route protection

---

## Permission Categories

| Category | Description |
|----------|-------------|
| Users | User account management |
| Roles | Role management |
| Federations | Federation management |
| Entities | Entity/club management |
| Licenses | License management |
| Certifications | Certification management |
| Events | Event management |
| Documents | Document management |
| Settings | System settings |

---

## Route Protection

### How It Works
1. Admin maps permission to route
2. Middleware checks user has permission
3. Access granted or denied

### Coverage Statistics
- Shows percentage of protected routes
- Identifies unprotected routes
- Suggests permissions based on route patterns

---

## Business Rules

### System Permissions
Certain core permissions are protected:
- Cannot be deleted
- Cannot be renamed
- Examples: `access users`, `manage roles`

### Permission Naming
- Lowercase with hyphens
- Pattern: `action resource` (e.g., `create user`, `access licenses`)

### Audit Trail
All permission changes are logged:
- Who made the change
- What was changed
- When it happened

---

## Related Documentation

- [Role Management](/access-control/role-management)
- [Individual Roles](/access-control/individual-roles)
- [Federation License Permissions](/access-control/federation-license-permissions)
