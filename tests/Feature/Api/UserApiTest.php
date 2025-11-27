<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_post_create_user_works()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/users/create', [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'password123',
                'role' => 'student',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'code' => 201,
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
        ]);
    }

    public function test_post_read_user_works()
    {
        $user = User::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/users/read', [
                'id' => $user->id,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'data' => [
                    'id' => $user->id,
                ],
            ]);
    }

    public function test_post_update_user_works()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/users/update', [
                'id' => $user->id,
                'name' => 'New Name',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
        ]);
    }

    public function test_post_delete_user_works()
    {
        $user = User::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/users/delete', [
                'id' => $user->id,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_validation_errors_return_422()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/users/create', [
                'name' => '', // Invalid: required
                'email' => 'invalid-email', // Invalid: must be email
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 422,
                'message' => 'Validation failed',
            ])
            ->assertJsonStructure([
                'errors' => [
                    'name',
                    'email',
                ],
            ]);
    }
}

