<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'status_description' => $this->status->getLabel(),
            'priority' => $this->priority,
            'due_at' => $this->due_at != null ? $this->due_at->format('Y-m-d') : null,
            'user_id' => $this->user != null ? $this->user->id : null,
            'user_name' => $this->user != null ? $this->user->name : null,
        ];
    }
}
