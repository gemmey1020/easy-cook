<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use RuntimeException;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        if ((string) $app['config']->get('app.env') === 'testing') {
            $expectedDatabase = 'easycook_25_3_2026_testing';
            $activeConnection = (string) $app['config']->get('database.default');
            $activeDatabase = (string) $app['config']->get("database.connections.{$activeConnection}.database");

            if ($activeConnection !== 'mysql' || $activeDatabase !== $expectedDatabase) {
                throw new RuntimeException(
                    "Unsafe PHPUnit database target detected. Expected mysql/{$expectedDatabase}, got {$activeConnection}/{$activeDatabase}."
                );
            }
        }

        return $app;
    }
}
