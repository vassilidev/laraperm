<?php

namespace Vassilidev\Laraperm\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Vassilidev\Laraperm\Laraperm
 */
class Laraperm extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Vassilidev\Laraperm\Laraperm::class;
    }
}
