<?php

namespace Henrist\LaravelApiQuery\Facades;

use Illuminate\Support\Facades\Facade;

class ApiQuery extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'ApiQuery';
    }
}
