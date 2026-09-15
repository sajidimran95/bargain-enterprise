<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

use function Livewire\on;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Livewire 3.8 bug: file download responses leak into effects.returns and
        // break JSON encoding. Normalize after dehydrate hooks run.
        on('dehydrate', function ($component, $context): void {
            if (! isset($context->effects['returns']) || ! is_array($context->effects['returns'])) {
                return;
            }

            $context->effects['returns'] = array_map(
                function (mixed $value): mixed {
                    if ($value instanceof StreamedResponse || $value instanceof BinaryFileResponse) {
                        return null;
                    }

                    return $value;
                },
                $context->effects['returns']
            );
        });

        Gate::before(function ($user, string $ability) {
            if (! method_exists($user, 'hasPermission')) {
                return null;
            }

            if (method_exists($user, 'hasRole') && $user->hasRole('owner')) {
                return true;
            }

            // Dotted abilities map to application permissions (e.g. invoice.create).
            if (str_contains($ability, '.')) {
                return $user->hasPermission($ability);
            }

            return null;
        });
    }
}
