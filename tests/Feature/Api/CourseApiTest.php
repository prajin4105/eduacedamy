<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'instructor']);
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_post_update_course_works()
    {
        $course = Course::factory()->create([
            'instructor_id' => $this->user->id,
            'title' => 'Old Title',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/courses/update', [
                'id' => $course->id,
                'title' => 'New Title',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
            ]);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'New Title',
        ]);
    }

    public function test_post_read_course_works()
    {
        $course = Course::factory()->create([
            'instructor_id' => $this->user->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/courses/read', [
                'id' => $course->id,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'data' => [
                    'id' => $course->id,
                ],
            ]);
    }
}

