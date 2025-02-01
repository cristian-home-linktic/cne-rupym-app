<?php

namespace Tests\Feature\Middleware;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DevelopmentModeOnlyTest extends TestCase
{
    #[Test]
    public function development_routes_are_accessible_in_local_environment(): void
    {
        app()->detectEnvironment(fn () => 'local');

        $this->get('/phpinfo')->assertOk();
        $this->get('/xdebug')->assertOk();
    }

    #[Test]
    public function development_routes_are_forbidden_in_production_environment(): void
    {
        app()->detectEnvironment(fn () => 'production');

        $this->get('/phpinfo')->assertForbidden();
        $this->get('/xdebug')->assertForbidden();
    }
}
