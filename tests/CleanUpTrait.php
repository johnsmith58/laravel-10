<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Facade;
use Mockery;

trait CleanUpTrait
{
    protected function tearDown(): void
    {
        Mockery::close();
        Facade::clearResolvedInstances();
    }
}