<?php
namespace Midnite81\Prowl\Facades;

use Illuminate\Support\Facades\Facade;

class Prowl extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'midnite81.prowl'; }
}