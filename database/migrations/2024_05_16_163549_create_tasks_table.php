<?php

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id()->index();
            $table->timestamps();

            $table->string('name')->index();
            $table->text('description')->nullable()->index();

            // Depending on how often users filter by other columns, it might be better to index those too
            $table->string('status')->default(TaskStatusEnum::OPEN->value);
            $table->string('priority');
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('due_at')->nullable();

            $table->foreignId('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
