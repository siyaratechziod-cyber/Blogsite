<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

abstract class Controller
{
    use AuthorizesRequests;

    /**
     * Page size requested by an API client (?per_page=), clamped to 1..50.
     */
    protected function perPage(Request $request, int $default = 15): int
    {
        return max(1, min(50, (int) $request->input('per_page', $default)));
    }
}
