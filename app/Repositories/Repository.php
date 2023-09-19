<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    private Application $app;

    public Model $model;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->init();
    }

    abstract protected function model(): string;

    private function init(): void
    {
        $model = $this->app->make($this->model());

        $this->model = $model;
    }
}
