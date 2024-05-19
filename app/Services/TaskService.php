<?php

namespace App\Services;

use App\Enums\TaskStatusEnum;
use App\Http\Resources\TaskDetailsResource;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TaskService
{

    public function getTasks(Request $request): array
    {
        $tasks = Task::query()
                ->filter($request->only('search'))
                ->paginate(10);

        return [
            'filters' => $request->all('search'),
            'tasks' => TaskDetailsResource::collection($tasks)
        ];
    }

    public function create(array $data, int $user_id): Model|Builder
    {
        $data['user_id'] = $user_id;

        return Task::query()->create($data);
    }

    public function update(Task $task, array $data): void
    {
        $task->update($data);
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function takeOnTask(Task $task, int $userId): void
    {
        $task->update([
            'user_id' => $userId,
            'status' => TaskStatusEnum::IN_PROGRESS
        ]);
    }
}
