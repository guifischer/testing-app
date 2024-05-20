<?php

namespace Tests\Feature;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TasksTest extends TestCase
{

    use RefreshDatabase;

    public function test_list_user_tasks(): void
    {
        // Maybe add in test case as well
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/dashboard');

        $response->assertOk();
    }

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();

        $taskData = [
            'name' => 'Test task',
            'description' => 'Test description',
            'priority' => TaskPriorityEnum::MEDIUM->value,
            'status' => TaskStatusEnum::OPEN->value,
            'due_at' => '2022-01-01 00:00:00',
        ];

        $response = $this
            ->actingAs($user)
            ->post('/tasks', $taskData);

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/dashboard');

        $this->assertDatabaseHas('tasks', $taskData);
    }

    public function test_user_can_assign_task()
    {
        $user = User::factory()->create();
        $task = $this->createTask();

        $response = $this->actingAs($user)
            ->patch('/tasks/'.$task->id.'/takeon');

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/dashboard');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatusEnum::IN_PROGRESS->value,
        ]);
    }

    public function test_user_can_update_own_task()
    {
        $user = User::factory()->create();
        $task = $this->createTask($user->id);

        $taskData = [
            'name' => 'new Test task',
            'description' => 'new Test description',
            'priority' => TaskPriorityEnum::MEDIUM->value,
            'status' => TaskStatusEnum::IN_PROGRESS->value,
            'due_at' => '2022-01-02 00:00:00',
        ];

        $response = $this->actingAs($user)
            ->patch('/tasks/'.$task->id, $taskData);

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/dashboard');

        $this->assertDatabaseHas('tasks', $taskData);
    }

    public function test_user_can_delete_own_task()
    {
        $user = User::factory()->create();
        $task = $this->createTask($user->id);

        $response = $this->actingAs($user)->delete('/tasks/' . $task->id);

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/dashboard');

        $this->assertDatabaseEmpty('tasks');
    }

    public function test_user_cannot_assign_other_users_tasks()
    {
        $randomUser = User::factory()->create();
        $task = $this->createTask($randomUser->id);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patch('/tasks'.$task->id.'/takeon');

        $response->assertNotFound();
    }

    public function test_user_cannot_update_other_users_tasks()
    {
        $randomUser = User::factory()->create();
        $task = $this->createTask($randomUser->id);

        $user = User::factory()->create();

        $taskData = [
            'name' => 'new Test task',
            'description' => 'new Test description',
            'priority' => TaskPriorityEnum::MEDIUM->value,
            'status' => TaskStatusEnum::IN_PROGRESS->value,
            'due_at' => '2022-01-02 00:00:00',
        ];

        $response = $this->actingAs($user)
            ->patch('/tasks/'.$task->id, $taskData);

        $response->assertNotFound();
    }

    public function test_user_cannot_delete_other_users_tasks()
    {
        $randomUser = User::factory()->create();
        $task = $this->createTask($randomUser->id);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete('/tasks/' . $task->id);

        $response->assertNotFound();
    }
}
