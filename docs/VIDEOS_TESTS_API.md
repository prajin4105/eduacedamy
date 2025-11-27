# Videos and Tests API Documentation

## Overview
This document describes the APIs for managing videos and tests in courses, with proper role-based access control.

## Videos API

### Access Control

| Action | Admin | Instructor | Student |
|--------|-------|------------|---------|
| List videos | ✅ All | ✅ All | ✅ Published only |
| View video | ✅ All | ✅ All + Own unpublished | ✅ Published only |
| Create video | ✅ | ✅ (own courses) | ❌ |
| Update video | ✅ Any | ✅ Own courses only | ❌ |
| Delete video | ✅ Any | ✅ Own courses only | ❌ |

### Endpoints

#### List Videos for a Course
```
GET /api/videos?course_id={course_id}
POST /api/videos/read
```

**Body (POST):**
```json
{
  "id": 1
}
```

**Response:**
```json
[
  {
    "id": 1,
    "course_id": 1,
    "title": "Introduction Video",
    "description": "Course introduction",
    "video_url": "http://...",
    "thumbnail_url": "http://...",
    "duration_seconds": 300,
    "sort_order": 1,
    "is_published": true
  }
]
```

#### Create Video
```
POST /api/videos
POST /api/videos/create
```

**Body:**
```json
{
  "course_id": 1,
  "title": "New Video",
  "description": "Video description",
  "video_url": "path/to/video.mp4",
  "duration_seconds": 600,
  "sort_order": 1,
  "is_published": true
}
```

**Multipart Form Data (with thumbnail):**
- `course_id`: integer (required)
- `title`: string (required)
- `description`: string (optional)
- `video_url`: string (required)
- `thumbnail_file`: image file (optional, max 2MB)
- `duration_seconds`: integer (optional)
- `sort_order`: integer (optional)
- `is_published`: boolean (optional)

#### Update Video
```
PUT /api/videos/{id}
POST /api/videos/update
```

**Body (POST):**
```json
{
  "id": 1,
  "title": "Updated Title",
  "description": "Updated description"
}
```

#### Delete Video
```
DELETE /api/videos/{id}
POST /api/videos/delete
```

**Body (POST):**
```json
{
  "id": 1
}
```

## Tests API

### Access Control

| Action | Admin | Instructor | Student |
|--------|-------|------------|---------|
| List tests | ✅ All | ✅ Own courses | ✅ Enrolled courses |
| View test | ✅ Any | ✅ Own courses | ✅ If enrolled |
| Create test | ✅ | ✅ (own courses) | ❌ |
| Update test | ✅ Any | ✅ Own courses only | ❌ |
| Delete test | ✅ Any | ✅ Own courses only | ❌ |
| Take test | ✅ | ✅ | ✅ (if enrolled & completed videos) |
| Submit test | ✅ | ✅ | ✅ (if enrolled & completed videos) |

### Endpoints

#### List Tests
```
GET /api/tests?course_id={course_id}
POST /api/tests/read
```

#### Create Test
```
POST /api/tests
POST /api/tests/create
```

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

#### Update Test
```
PUT /api/tests/{id}
POST /api/tests/update
```

**Body (POST):**
```json
{
  "id": 1,
  "title": "Updated Test Title",
  "passing_score": 80
}
```

#### Delete Test
```
DELETE /api/tests/{id}
POST /api/tests/delete
```

**Body (POST):**
```json
{
  "id": 1
}
```

#### Take Test (Student)
```
GET /api/test/{courseId}
```

**Requirements:**
- Student must be enrolled in the course
- Student must have completed all videos (100% progress)
- Student must not have already passed the test

**Response:**
```json
{
  "test": {
    "id": 1,
    "title": "Final Exam",
    "description": "...",
    "course_id": 1,
    "max_attempts": 3,
    "total_questions": 5
  },
  "questions": [
    {
      "id": 1,
      "question_text": "What is...?",
      "options": {
        "a": "Option A",
        "b": "Option B",
        "c": "Option C",
        "d": "Option D"
      }
    }
  ]
}
```

#### Submit Test
```
POST /api/test/submit
```

**Body:**
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

**Response:**
```json
{
  "score": 80,
  "passed": true,
  "attempt_number": 1
}
```

#### Get Test Status
```
GET /api/test/status/{courseId}
```

**Response:**
```json
{
  "hasTest": true,
  "passed": false,
  "attempts": [
    {
      "id": 1,
      "score": 60,
      "passed": false,
      "attempt_number": 1,
      "attempted_at": "2025-01-01T00:00:00.000000Z"
    }
  ],
  "attemptsRemaining": 2,
  "hasCertificate": false,
  "maxAttempts": 3
}
```

## Image/Thumbnail Uploads

### Course Images
Use the general upload endpoint:
```
POST /api/uploads
```

**Multipart Form Data:**
- `file`: image file (required, max 10MB)
- `folder`: string (optional, default: "uploads")

**Response:**
```json
{
  "success": true,
  "code": 201,
  "message": "File uploaded successfully",
  "data": {
    "path": "uploads/filename.jpg",
    "url": "http://.../storage/uploads/filename.jpg",
    "filename": "filename.jpg",
    "size": 1024000,
    "mime_type": "image/jpeg"
  }
}
```

### Video Thumbnails
When creating/updating videos, include `thumbnail_file` in the request:
```
POST /api/videos/create
```

**Multipart Form Data:**
- `course_id`: integer
- `title`: string
- `thumbnail_file`: image file (max 2MB)
- ... other fields

The thumbnail will be automatically stored in `video-thumbnails/` directory.

## Notes

1. **Video URLs**: Can be stored as:
   - Relative paths (stored in database, accessed via `storage/` prefix)
   - Full URLs (external video hosting)

2. **Thumbnail URLs**: Automatically prefixed with `storage/` when accessed via API (model accessor).

3. **Test Questions**: Managed separately via TestQuestion model (not included in this API).

4. **Authorization**: 
   - Instructors can only manage videos/tests for courses they own
   - Students can only view published videos and take tests for enrolled courses
   - Admin has full access

5. **File Storage**: All uploaded files are stored in `storage/app/public/` and accessible via `storage/` symlink.

