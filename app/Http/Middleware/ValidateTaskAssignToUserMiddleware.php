<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateTaskAssignToUserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $task = $request->route('task');

        if ($task->user_id !== Auth::id()) {
            abort(404);
        }

        return $next($request);
    }
}
