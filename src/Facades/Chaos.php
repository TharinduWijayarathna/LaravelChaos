<?php

namespace LaravelChaos\Testing\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LaravelChaos\Testing\Chaos
 */
class Chaos extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelChaos\Testing\Chaos::class;
    }
}
