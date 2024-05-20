<?php

namespace Tests;

use App\Models\Task;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createMultipleTasks(?int $user_id = null, ?int $count = 3): void
    {
        Task::factory($count)->create([
            'user_id' => $user_id
        ]);
    }

    public function createTask(?int $user_id = null): Model|Builder
    {
        return TaskFactory::new()->create([
            'user_id' => $user_id
        ]);
    }
}
