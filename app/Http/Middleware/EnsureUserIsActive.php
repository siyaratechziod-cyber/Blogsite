<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Rejects API tokens that belong to a deactivated account.
 */
class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->user()->isActive()) {
            return response()->json([
                'message' => 'This account has been deactivated. Please contact the site administrator.',
            ], 403);
        }

        return $next($request);
    }
}
