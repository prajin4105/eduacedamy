# EduAcademy API Documentation

## Overview

This API provides a standardized, token-based authentication system with consistent JSON responses for all endpoints. The API supports both RESTful endpoints (GET/PUT/DELETE) for frontend compatibility and POST-based CRUD endpoints for Postman and external integrations.

## Base URL

```
http://localhost:8000/api
```

## Authentication

All protected endpoints require a Bearer token in the Authorization header.

**Note:** Login and Register endpoints are PUBLIC and do NOT require authentication (you need them to get a token in the first place!).

### Headers

For protected endpoints:
```
Authorization: Bearer <token>
Accept: application/json
Content-Type: application/json
```

For public endpoints (login/register):
```
Accept: application/json
Content-Type: application/json
```

### Getting a Token

1. **Login** - POST `/api/auth/login` (PUBLIC - no token required)
   ```json
   {
     "email": "user@example.com",
     "password": "password123",
     "device_name": "Postman"
   }
   ```

   Response:
   ```json
   {
     "success": true,
     "code": 200,
     "message": "Login successful",
     "data": {
       "token": "1|xxxxxxxxxxxx",
       "token_type": "Bearer",
       "user": { ... }
     }
   }
   ```

2. **Register** - POST `/api/auth/register` (PUBLIC - no token required)
   ```json
   {
     "name": "New User",
     "email": "newuser@example.com",
     "password": "password123",
     "password_confirmation": "password123",
     "role": "student"
   }
   ```

### Unauthorized Response

If a protected endpoint is accessed without a valid token:

```json
{
  "success": false,
  "code": 401,
  "message": "Unauthenticated. Provide a valid Authorization: Bearer <token>",
  "errors": null
}
```

## Standard Response Format

### Success Response

```json
{
  "success": true,
  "code": 200,
  "message": "Resource updated successfully",
  "data": { ... },
  "meta": null
}
```

### Error Response

```json
{
  "success": false,
  "code": 422,
  "message": "Validation failed",
  "errors": {
    "field": ["error 1", "error 2"]
  }
}
```

### HTTP Status Codes

- `200` - Success
- `201` - Created
- `204` - No Content
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## POST-Based CRUD Endpoints

For Postman and external integrations, all resources support POST-based CRUD operations:

### Pattern

- `POST /api/{resource}/create` - Create a new resource
- `POST /api/{resource}/read` - Read a resource by ID
- `POST /api/{resource}/update` - Update a resource
- `POST /api/{resource}/delete` - Delete a resource

### Example: Users

#### Create User
```
POST /api/users/create
```

Body:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "role": "student"
}
```

#### Read User
```
POST /api/users/read
```

Body:
```json
{
  "id": 1
}
```

#### Update User
```
POST /api/users/update
```

Body:
```json
{
  "id": 1,
  "name": "Updated Name",
  "email": "updated@example.com"
}
```

#### Delete User
```
POST /api/users/delete
```

Body:
```json
{
  "id": 1
}
```

## Available Resources

### Authentication

- `POST /api/auth/login` - Login and get token
- `POST /api/auth/register` - Register new user
- `POST /api/auth/logout` - Logout (revoke token)
- `POST /api/auth/me` - Get current authenticated user
- `GET /api/me` - Get current authenticated user (REST)

### Users

- `POST /api/users/create` - Create user
- `POST /api/users/read` - Read user by ID
- `POST /api/users/update` - Update user
- `POST /api/users/delete` - Delete user
- `GET /api/users` - List users (REST)
- `GET /api/users/{id}` - Get user (REST)
- `PUT /api/users/{id}` - Update user (REST)
- `DELETE /api/users/{id}` - Delete user (REST)

### Courses

- `POST /api/courses/create` - Create course
- `POST /api/courses/read` - Read course by ID
- `POST /api/courses/update` - Update course
- `POST /api/courses/delete` - Delete course
- `GET /api/courses` - List courses (public)
- `GET /api/courses/{slug}` - Get course by slug (public)

### Enrollments

- `POST /api/enrollments/create` - Create enrollment
- `POST /api/enrollments/read` - Read enrollment by ID
- `POST /api/enrollments/update` - Update enrollment
- `POST /api/enrollments/delete` - Delete enrollment
- `GET /api/enrollments` - List enrollments (REST)
- `POST /api/enrollments` - Create enrollment (REST)

### Teams

- `POST /api/teams/create` - Create team
- `POST /api/teams/read` - Read team by ID
- `POST /api/teams/update` - Update team
- `POST /api/teams/delete` - Delete team
- `GET /api/teams` - List teams (REST)

### Tournaments

- `POST /api/tournaments/create` - Create tournament
- `POST /api/tournaments/read` - Read tournament by ID
- `POST /api/tournaments/update` - Update tournament
- `POST /api/tournaments/delete` - Delete tournament
- `GET /api/tournaments` - List tournaments (REST)

### Leaderboards

- `POST /api/leaderboards/create` - Create leaderboard
- `POST /api/leaderboards/read` - Read leaderboard by ID
- `POST /api/leaderboards/update` - Update leaderboard
- `POST /api/leaderboards/delete` - Delete leaderboard
- `GET /api/leaderboards` - List leaderboards (REST)

### Settings

- `POST /api/settings/read` - Read setting by key
- `POST /api/settings/update` - Update settings
- `GET /api/settings` - List all settings
- `GET /api/settings/{key}` - Get setting by key

### Uploads

- `POST /api/uploads` - Upload single file
  - Body: `multipart/form-data`
  - Fields: `file` (required), `folder` (optional)
  
- `POST /api/uploads/multiple` - Upload multiple files
  - Body: `multipart/form-data`
  - Fields: `files[]` (required, array), `folder` (optional)

## Validation

All endpoints use FormRequest validation. Validation errors return a 422 status code with the following format:

```json
{
  "success": false,
  "code": 422,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 6 characters."]
  }
}
```

## Error Handling

The API uses a global exception handler that automatically formats errors for API routes:

- **ValidationException** → 422 with errors object
- **AuthenticationException** → 401 with unauthenticated message
- **AuthorizationException** → 403 with forbidden message
- **ModelNotFoundException** → 404 with not found message
- **Generic Exception** → 500 with error message (detailed in debug mode)

## Postman Collection

Import the Postman collection from `postman/collection.json`:

1. Open Postman
2. Click "Import"
3. Select `postman/collection.json`
4. The collection includes:
   - Pre-configured authentication
   - All POST-based CRUD endpoints
   - Example requests with sample data
   - Auto-token extraction from login response

### Using the Collection

1. Set the `base_url` variable to your API base URL
2. Run the "Login" request to get a token (automatically saved)
3. All other requests will use the token automatically

## Testing

Run the PHPUnit tests:

```bash
php artisan test
```

Test files:
- `tests/Feature/Api/AuthenticationTest.php` - Authentication tests
- `tests/Feature/Api/UserApiTest.php` - User CRUD tests
- `tests/Feature/Api/CourseApiTest.php` - Course CRUD tests

## Notes

- All timestamps are in UTC
- File uploads are limited to 10MB per file
- Pagination is available on list endpoints (default: 15 per page, max: 100)
- The API preserves all existing frontend routes (GET/PUT/DELETE) for backward compatibility

