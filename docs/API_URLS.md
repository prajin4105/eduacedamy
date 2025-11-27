# API URLs Reference

## Base URL
```
http://localhost:8000/api
```

## Authentication URLs

### Public (No Token Required)
- `POST /api/login` - Login
- `POST /api/register` - Register
- `POST /api/auth/login` - Login (POST version)
- `POST /api/auth/register` - Register (POST version)
- `POST /api/forgot-password` - Forgot password
- `POST /api/verify-otp` - Verify OTP
- `POST /api/reset-password` - Reset password

### Protected (Token Required)
- `POST /api/logout` - Logout
- `GET /api/me` - Get current user (REST)
- `POST /api/auth/me` - Get current user (POST)
- `POST /api/auth/logout` - Logout (POST)

---

## Videos API URLs

### List Videos
- `GET /api/videos?course_id={course_id}` - List videos for a course (REST)
- `POST /api/videos/read` - Read video (POST) - requires `{"id": 1}` in body

### Create Video
- `POST /api/videos` - Create video (REST)
- `POST /api/videos/create` - Create video (POST)

**Body:**
```json
{
  "course_id": 1,
  "title": "Video Title",
  "description": "Description",
  "video_url": "path/to/video.mp4",
  "duration_seconds": 600,
  "sort_order": 1,
  "is_published": true
}
```

**With Thumbnail (Multipart):**
- `course_id`: integer
- `title`: string
- `thumbnail_file`: image file (max 2MB)
- Other fields as above

### Update Video
- `PUT /api/videos/{id}` - Update video (REST)
- `PATCH /api/videos/{id}` - Update video (REST)
- `POST /api/videos/update` - Update video (POST)

**Body (POST):**
```json
{
  "id": 1,
  "title": "Updated Title",
  "description": "Updated description"
}
```

### Delete Video
- `DELETE /api/videos/{id}` - Delete video (REST)
- `POST /api/videos/delete` - Delete video (POST)

**Body (POST):**
```json
{
  "id": 1
}
```

### View Single Video
- `GET /api/videos/{id}` - Get video by ID (REST)

---

## Tests API URLs

### List Tests
- `GET /api/tests` - List all tests (REST)
- `GET /api/tests?course_id={course_id}` - List tests for a course (REST)
- `POST /api/tests/read` - Read test (POST) - requires `{"id": 1}` in body

### Create Test
- `POST /api/tests` - Create test (REST)
- `POST /api/tests/create` - Create test (POST)

**Body:**
```json
{
  "course_id": 1,
  "title": "Final Exam",
  "description": "Course final examination",
  "passing_score": 75,
  "max_attempts": 3,
  "total_questions": 10
}
```

### Update Test
- `PUT /api/tests/{id}` - Update test (REST)
- `PATCH /api/tests/{id}` - Update test (REST)
- `POST /api/tests/update` - Update test (POST)

**Body (POST):**
```json
{
  "id": 1,
  "title": "Updated Test",
  "passing_score": 80
}
```

### Delete Test
- `DELETE /api/tests/{id}` - Delete test (REST)
- `POST /api/tests/delete` - Delete test (POST)

**Body (POST):**
```json
{
  "id": 1
}
```

### View Single Test
- `GET /api/tests/{id}` - Get test by ID (REST)

### Student Test Taking (Existing Routes)
- `GET /api/test/{courseId}` - Get test for student to take
- `POST /api/test/submit` - Submit test answers
- `GET /api/test/status/{courseId}` - Get test status and attempts

**Submit Test Body:**
```json
{
  "course_id": 1,
  "answers": [
    {
      "question_id": 1,
      "selected_option": "a"
    },
    {
      "question_id": 2,
      "selected_option": "b"
    }
  ]
}
```

---

## Image/Thumbnail Upload URLs

### Upload Single File
- `POST /api/uploads` - Upload file

**Multipart Form Data:**
- `file`: file (required, max 10MB)
- `folder`: string (optional, default: "uploads")

**Response:**
```json
{
  "success": true,
  "code": 201,
  "data": {
    "path": "uploads/filename.jpg",
    "url": "http://.../storage/uploads/filename.jpg",
    "filename": "filename.jpg",
    "size": 1024000,
    "mime_type": "image/jpeg"
  }
}
```

### Upload Multiple Files
- `POST /api/uploads/multiple` - Upload multiple files

**Multipart Form Data:**
- `files[]`: array of files (required, max 10 files, 10MB each)
- `folder`: string (optional)

---

## Course Image Upload

For course images, use the upload endpoint and then update the course:

1. Upload image:
   ```
   POST /api/uploads
   ```
   Get the `path` from response

2. Update course with image path:
   ```
   POST /api/courses/update
   ```
   ```json
   {
     "id": 1,
     "image": "uploads/course-image.jpg"
   }
   ```

---

## Video Thumbnail Upload

Thumbnails can be uploaded directly when creating/updating videos:

**Create Video with Thumbnail:**
```
POST /api/videos/create
```

**Multipart Form Data:**
- `course_id`: 1
- `title`: "Video Title"
- `video_url`: "path/to/video.mp4"
- `thumbnail_file`: image file (max 2MB)
- `duration_seconds`: 600
- `sort_order`: 1
- `is_published`: true

---

## Complete URL Examples

### Videos
```
GET    http://localhost:8000/api/videos?course_id=1
POST   http://localhost:8000/api/videos/create
GET    http://localhost:8000/api/videos/1
PUT    http://localhost:8000/api/videos/1
DELETE http://localhost:8000/api/videos/1
POST   http://localhost:8000/api/videos/read
POST   http://localhost:8000/api/videos/update
POST   http://localhost:8000/api/videos/delete
```

### Tests
```
GET    http://localhost:8000/api/tests
GET    http://localhost:8000/api/tests?course_id=1
POST   http://localhost:8000/api/tests/create
GET    http://localhost:8000/api/tests/1
PUT    http://localhost:8000/api/tests/1
DELETE http://localhost:8000/api/tests/1
POST   http://localhost:8000/api/tests/read
POST   http://localhost:8000/api/tests/update
POST   http://localhost:8000/api/tests/delete
```

### Student Test Taking
```
GET  http://localhost:8000/api/test/1
POST http://localhost:8000/api/test/submit
GET  http://localhost:8000/api/test/status/1
```

### Uploads
```
POST http://localhost:8000/api/uploads
POST http://localhost:8000/api/uploads/multiple
```

---

## Headers Required

All protected endpoints require:
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

For file uploads:
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: multipart/form-data
```

---

## Quick Reference by Role

### Admin
- ✅ All URLs above are accessible

### Instructor
- ✅ Can use all video/test CRUD URLs for their own courses
- ✅ Can upload files
- ❌ Cannot manage videos/tests for other instructors' courses

### Student
- ✅ Can view published videos: `GET /api/videos?course_id={id}`
- ✅ Can take tests: `GET /api/test/{courseId}`, `POST /api/test/submit`
- ✅ Can check test status: `GET /api/test/status/{courseId}`
- ❌ Cannot create/update/delete videos or tests
- ❌ Cannot see unpublished videos

