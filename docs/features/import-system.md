---
title: Import System
description: Bulk individual import from CSV/XLS files
---

# Import System

## Overview

The Import System allows federation administrators to perform bulk imports of individuals from CSV or XLS files.

---

## Key Features

### File Support
- CSV, XLS, and XLSX formats
- Maximum file size: 10MB
- UTF-8 encoding support

### Field Mapping
- Flexible column mapping UI
- Map source columns to platform fields
- Save mappings for reuse

### Validation & Preview
- Validates all data before import
- Shows count of valid records
- Lists warnings (potential duplicates)
- Lists errors (invalid data)
- Downloadable error report

### Duplicate Handling
- Detects duplicates by name + surname + birthdate + country
- Resolution options: Skip or Update existing

### Processing
- Large files processed asynchronously
- Real-time progress tracking
- Background queue processing

---

## Import Workflow

1. **Upload File** - Select CSV/XLS file
2. **Map Fields** - Map columns to platform fields
3. **Preview** - Review validation results
4. **Resolve Duplicates** - Choose how to handle duplicates
5. **Import** - Process the import
6. **Review Results** - See success/failure counts

---

## Required Fields

| Field | Description |
|-------|-------------|
| Name | First name |
| Surname | Last name |
| Email | Valid email address |
| Birthdate | Date of birth |
| Country | Country code |

## Optional Fields

| Field | Description |
|-------|-------------|
| Phone | Phone number |
| Address | Street address |
| City | City name |
| Postal Code | ZIP/postal code |
| Gender | M/F |
| Nationality | Nationality code |

---

## Error Handling

### Validation Errors
- Missing required fields
- Invalid email format
- Invalid date format
- Invalid country code

### Warnings
- Potential duplicate records
- Fields exceeding length limits

### Recovery
- Failed imports can be retried
- Error report shows which rows failed
- Fix errors in source file and re-import
