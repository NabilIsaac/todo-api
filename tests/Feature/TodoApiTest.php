<?php

namespace Tests\Feature;

use App\Enums\TodoStatus;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_todos(): void
    {
        Todo::factory(3)->create();

        $response = $this->getJson('/api/todos');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_filter_todos_by_status(): void
    {
        Todo::factory(2)->create(['status' => TodoStatus::COMPLETED]);
        Todo::factory(3)->create(['status' => TodoStatus::IN_PROGRESS]);

        $response = $this->getJson('/api/todos?status=completed');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_search_todos(): void
    {
        Todo::factory()->create(['title' => 'Test Todo']);
        Todo::factory()->create(['title' => 'Another Todo']);

        $response = $this->getJson('/api/todos?search=Test');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_todo(): void
    {
        $todoData = [
            'title' => 'New Todo',
            'details' => 'Todo details',
            'status' => TodoStatus::NOT_STARTED->value,
        ];

        $response = $this->postJson('/api/todos', $todoData);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'New Todo',
                    'details' => 'Todo details',
                    'status' => TodoStatus::NOT_STARTED->value,
                ]
            ]);
    }

    public function test_can_update_todo(): void
    {
        $todo = Todo::factory()->create();

        $updateData = [
            'title' => 'Updated Todo',
            'status' => TodoStatus::COMPLETED->value,
        ];

        $response = $this->putJson("/api/todos/{$todo->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'Updated Todo',
                    'status' => TodoStatus::COMPLETED->value,
                ]
            ]);
    }

    public function test_can_delete_todo(): void
    {
        $todo = Todo::factory()->create();

        $response = $this->deleteJson("/api/todos/{$todo->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    public function test_validates_required_fields_when_creating_todo(): void
    {
        $response = $this->postJson('/api/todos', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'details']);
    }

    public function test_validates_status_enum_when_creating_todo(): void
    {
        $response = $this->postJson('/api/todos', [
            'title' => 'Test Todo',
            'details' => 'Details',
            'status' => 'invalid_status',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
