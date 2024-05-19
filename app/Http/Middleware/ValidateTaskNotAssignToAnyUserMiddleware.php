<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateTaskNotAssignToAnyUserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $task = $request->route('task');

        if (!empty($task->user_id)) {
            abort(404);
        }

        return $next($request);
    }
}
