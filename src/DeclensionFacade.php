<?php

namespace Maslauskas\Declension;

use Illuminate\Support\Facades\Facade;

/**
 * Class DeclensionFacade
 * @see \Maslauskas\Declension\Declension
 * @package Maslauskas\Declension
 */
class DeclensionFacade extends Facade
{
    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'declension';
    }
}