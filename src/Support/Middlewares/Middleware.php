<?php

declare(strict_types=1);

namespace Support\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface Middleware
{
    public function handle(Request $request, Closure $next): Response;
}
