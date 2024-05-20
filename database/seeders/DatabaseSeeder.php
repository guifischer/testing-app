<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Task::factory(10)->create();

        $tasks = Task::factory(10000)->make();
        $chunks = $tasks->chunk(500);

        $chunks->each(function($chunk){
           Task::query()->insert($chunk->toArray());
        });
    }
}
