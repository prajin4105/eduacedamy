# Role-Based Access Control (RBAC) Implementation

## Overview
This document describes the role-based access control system implemented for the EduAcademy API.

## Roles

1. **Admin** - Full access to all resources
2. **Instructor** - Can manage their own courses and view students
3. **Student** - Read-only access, can only modify their own profile

## Access Control Rules

### Users Management

| Action | Admin | Instructor | Student |
|--------|-------|------------|---------|
| List users | ✅ All users | ✅ Students only | ❌ Forbidden |
| View user | ✅ Any user | ✅ Own profile + Students | ✅ Own profile only |
| Create user | ✅ | ❌ | ❌ |
| Update user | ✅ Any user | ✅ Own profile only | ✅ Own profile only (no role change) |
| Delete user | ✅ | ❌ | ❌ |

**Notes:**
- Students cannot change their role
- Only admin can change user roles
- Instructors can view students (for course management purposes)

### Courses Management

| Action | Admin | Instructor | Student |
|--------|-------|------------|---------|
| List courses | ✅ All | ✅ All | ✅ Published only |
| View course | ✅ All | ✅ All + Own unpublished | ✅ Published only |
| Create course | ✅ | ✅ (auto-assigned as instructor) | ❌ |
| Update course | ✅ Any | ✅ Own courses only | ❌ |
| Delete course | ✅ Any | ✅ Own courses only | ❌ |

**Notes:**
- Instructors automatically assigned as `instructor_id` when creating courses
- Instructors cannot change `instructor_id` of courses
- Students can only view published courses

### Enrollments Management

| Action | Admin | Instructor | Student |
|--------|-------|------------|---------|
| List enrollments | ✅ All | ✅ For their courses | ✅ Own enrollments only |
| View enrollment | ✅ Any | ✅ For their courses | ✅ Own enrollments only |
| Create enrollment | ✅ Any | ❌ | ✅ Self-enrollment only |
| Update enrollment | ✅ Any | ✅ For their courses | ❌ |
| Delete enrollment | ✅ Any | ❌ | ✅ Own enrollments only |

**Notes:**
- Students can only enroll themselves
- Students cannot update enrollments (only admin/instructors can update progress)
- Instructors can view/update enrollments for their courses

## Policies Created

1. **CoursePolicy** (`app/Policies/CoursePolicy.php`)
   - Controls access to course resources
   - Checks instructor ownership for update/delete

2. **UserPolicy** (`app/Policies/UserPolicy.php`)
   - Controls access to user resources
   - Restricts students from viewing other users
   - Allows instructors to view students

3. **EnrollmentPolicy** (`app/Policies/EnrollmentPolicy.php`)
   - Controls access to enrollment resources
   - Filters enrollments by role
   - Prevents students from updating enrollments

## Implementation Details

### Authorization Checks

All controllers use Laravel's `authorize()` method:

```php
// Example from CourseController
$this->authorize('update', $course);
```

### Automatic Role-Based Filtering

- **Students**: Automatically filtered to see only their own data
- **Instructors**: Filtered to see only their courses and related enrollments
- **Admin**: No filtering, sees everything

### Response Format

Unauthorized requests return:
```json
{
  "success": false,
  "code": 403,
  "message": "This action is unauthorized.",
  "errors": null
}
```

## Testing

To test RBAC:

1. **As Student:**
   - ✅ Can view own profile
   - ✅ Can update own profile (except role)
   - ✅ Can view published courses
   - ✅ Can enroll in courses
   - ❌ Cannot list users
   - ❌ Cannot create/update/delete courses
   - ❌ Cannot update enrollments

2. **As Instructor:**
   - ✅ Can create courses (auto-assigned as instructor)
   - ✅ Can update/delete own courses
   - ✅ Can view students
   - ✅ Can view/update enrollments for their courses
   - ❌ Cannot update other instructors' courses
   - ❌ Cannot create/delete users

3. **As Admin:**
   - ✅ Full access to all resources
   - ✅ Can manage users, courses, enrollments
   - ✅ No restrictions

## Security Notes

- All protected endpoints require authentication (`auth:sanctum` middleware)
- Policies are automatically enforced via `authorize()` calls
- Students are prevented from accessing admin/instructor-only data
- Role changes are restricted to admin only
- Instructors cannot modify course ownership

