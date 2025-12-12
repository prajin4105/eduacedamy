<?php

namespace Tests\Feature\Api;

use App\Models\Chat;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_start_chat_without_enrollment(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $instructor = User::factory()->create(['role' => 'instructor']);

        $course = Course::create([
            'title' => 'Test Course',
            'slug' => 'test-course',
            'price' => 0,
            'instructor_id' => $instructor->id,
        ]);

        $response = $this->actingAs($student, 'sanctum')
            ->postJson("/api/courses/{$course->id}/chat");

        $response->assertStatus(403)
            ->assertJson(['success' => false]);
    }

    public function test_student_can_start_chat_when_enrolled(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $instructor = User::factory()->create(['role' => 'instructor']);

        $course = Course::create([
            'title' => 'Test Course',
            'slug' => 'test-course',
            'price' => 0,
            'instructor_id' => $instructor->id,
        ]);

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'enrolled',
        ]);

        $response = $this->actingAs($student, 'sanctum')
            ->postJson("/api/courses/{$course->id}/chat");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'course_id' => $course->id,
                    'student_id' => $student->id,
                    'instructor_id' => $instructor->id,
                ],
            ]);

        $this->assertDatabaseHas('chats', [
            'course_id' => $course->id,
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
        ]);
    }

    public function test_instructor_can_reply_and_increment_student_unread(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $instructor = User::factory()->create(['role' => 'instructor']);

        $course = Course::create([
            'title' => 'Test Course',
            'slug' => 'test-course',
            'price' => 0,
            'instructor_id' => $instructor->id,
        ]);

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'enrolled',
        ]);

        $chat = Chat::create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
            'status' => 'open',
            'last_message_at' => now(),
        ]);

        $response = $this->actingAs($instructor, 'sanctum')
            ->postJson("/api/chats/{$chat->id}/messages", [
                'body' => 'Hello student',
            ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('chat_messages', [
            'chat_id' => $chat->id,
            'sender_id' => $instructor->id,
            'body' => 'Hello student',
        ]);

        $chat->refresh();
        $this->assertEquals(1, $chat->student_unread_count);
    }
}
