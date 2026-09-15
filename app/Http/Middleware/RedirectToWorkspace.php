<?php

namespace App\Http\Middleware;

use App\Support\Workspace\WorkspaceCatalog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToWorkspace
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->boolean('embed')) {
            return $next($request);
        }

        if ($request->header('X-Workspace-Embed') === '1') {
            return $next($request);
        }

        // Iframe navigations must stay embedded — never bounce to the shell (nested chrome).
        if ($request->header('Sec-Fetch-Dest') === 'iframe') {
            if ($request->isMethod('GET') && ! $request->boolean('embed')) {
                return redirect()->to($request->fullUrlWithQuery(['embed' => 1]));
            }

            return $next($request);
        }

        if (! $request->isMethod('GET')) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return $next($request);
        }

        if ($request->is('livewire/*') || $request->is('livewire/*/*')) {
            return $next($request);
        }

        // Keep existing feature tests hitting module routes directly.
        // Workspace redirect behavior is covered explicitly in WorkspaceNavigationTest.
        if (app()->runningUnitTests() && ! $request->boolean('workspace_test_redirect')) {
            return $next($request);
        }

        $route = $request->route();
        $routeName = $route?->getName();

        if (! is_string($routeName) || $routeName === '') {
            return $next($request);
        }

        if (in_array($routeName, WorkspaceCatalog::excludedRouteNames(), true)) {
            return $next($request);
        }

        if (str_ends_with($routeName, '.pdf') || str_contains($routeName, 'password') || str_contains($routeName, 'verification')) {
            return $next($request);
        }

        // Allow explicit bypass for tooling / tests.
        if ($request->boolean('workspace_bypass')) {
            return $next($request);
        }

        $params = $route->parameters();
        $query = ['open' => $routeName];

        foreach ($params as $key => $value) {
            if (is_object($value) && method_exists($value, 'getRouteKey')) {
                $query[$key] = $value->getRouteKey();
            } elseif (is_scalar($value) || $value === null) {
                $query[$key] = $value;
            }
        }

        return redirect()->route('dashboard', $query);
    }
}
