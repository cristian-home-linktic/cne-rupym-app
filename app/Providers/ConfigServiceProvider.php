<?php

namespace App\Providers;

use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Repository::macro('stringOrNull', function (string $key, $default = null): ?string {
            /** @var Repository $this */
            $value = $this->get($key, $default);

            return is_string($value) ? $value : null;
        });

        Repository::macro('integerOrNull', function (string $key, $default = null): ?int {
            /** @var Repository $this */
            $value = $this->get($key, $default);

            return is_numeric($value) ? (int) $value : null;
        });

        Repository::macro('floatOrNull', function (string $key, $default = null): ?float {
            /** @var Repository $this */
            $value = $this->get($key, $default);

            return is_numeric($value) ? (float) $value : null;
        });

        Repository::macro('booleanOrNull', function (string $key, $default = null): ?bool {
            /** @var Repository $this */
            $value = $this->get($key, $default);

            return is_bool($value) ? $value : null;
        });

        Repository::macro('arrayOrNull', function (string $key, $default = null): ?array {
            /** @var Repository $this */
            $value = $this->get($key, $default);

            return is_array($value) ? $value : null;
        });
    }
}
