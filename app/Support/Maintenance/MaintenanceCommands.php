<?php

namespace App\Support\Maintenance;

use Illuminate\Support\Facades\Artisan;
use InvalidArgumentException;

class MaintenanceCommands
{
    /**
     * @return array<string, array{label: string, command: string, parameters: array<string, mixed>}>
     */
    public function scripts(): array
    {
        return [
            'cache' => [
                'label' => 'Cache clear',
                'command' => 'cache:clear',
                'parameters' => [],
            ],
            'optimize' => [
                'label' => 'Optimize clear',
                'command' => 'optimize:clear',
                'parameters' => [],
            ],
            'migrate' => [
                'label' => 'Run migrations',
                'command' => 'migrate',
                'parameters' => ['--force' => true],
            ],
        ];
    }

    public function run(string $script): string
    {
        $definition = $this->scripts()[$script] ?? null;

        if ($definition === null) {
            throw new InvalidArgumentException("Unknown maintenance script [{$script}].");
        }

        set_time_limit(180);

        $exitCode = Artisan::call($definition['command'], $definition['parameters']);
        $output = trim(Artisan::output());

        if ($exitCode !== 0) {
            return trim($output."\nExit code: {$exitCode}");
        }

        return $output !== '' ? $output : 'Finished.';
    }
}
