<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

if (!function_exists('role_prefix')) {
    /**
     * Route-name prefix for the currently authenticated user's role.
     */
    function role_prefix(): string
    {
        return Auth::check() && Auth::user()->isAdmin() ? 'admin.' : 'petugas.';
    }
}

if (!function_exists('role_route')) {
    /**
     * Build a URL for the current user's role-prefixed route.
     *
     * Views are shared between the "admin" and "petugas" areas, so a link to
     * e.g. "pasien.index" must resolve to "admin.pasien.index" for admins and
     * "petugas.pasien.index" for petugas. Falls back to a non-prefixed route
     * (or "#" when the current role has no access to the target).
     */
    function role_route(string $name, mixed $parameters = []): string
    {
        foreach ([role_prefix() . $name, $name] as $candidate) {
            if (Route::has($candidate)) {
                return route($candidate, $parameters);
            }
        }

        return '#';
    }
}

if (!function_exists('role_route_is')) {
    /**
     * Determine whether the current request matches the given role-prefixed
     * route pattern(s). Used for active navigation highlighting.
     */
    function role_route_is(string ...$patterns): bool
    {
        $patterns = array_map(fn (string $pattern) => role_prefix() . $pattern, $patterns);

        return request()->routeIs(...$patterns);
    }
}
