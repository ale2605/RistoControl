<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRestaurantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if (! $user->isSuperAdmin() && ! $user->restaurant_id) {
            abort(403, 'Utente non associato a un ristorante.');
        }

        if (! $user->isSuperAdmin()) {
            app()->instance('currentRestaurantId', $user->restaurant_id);
        }

        return $next($request);
    }
}
