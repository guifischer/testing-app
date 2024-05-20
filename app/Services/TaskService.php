<?php

namespace App\Services;

use App\Enums\TaskStatusEnum;
use App\Http\Resources\TaskDetailsResource;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskService
{

    public function getTasks(Request $request): AnonymousResourceCollection
    {
        $orderBy = empty($request->get('order_by')) ? 'created_at' : $request->get('order_by');
        $orderDirection = !empty($request->get('order_direction')) ? $request->get('order_direction') : 'desc';

        $tasks = Task::query()
                ->filter($request->only('search', 'status', 'owner'))
                ->orderBy($orderBy, $orderDirection)
                ->paginate(10)
                ->withQueryString();

        return TaskDetailsResource::collection($tasks);
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
