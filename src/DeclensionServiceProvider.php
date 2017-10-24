<?php

namespace Maslauskas\Declension;

use Illuminate\Support\ServiceProvider;

/**
 * Class DeclensionServiceProvider
 * @package Maslauskas\Declension
 */
class DeclensionServiceProvider extends ServiceProvider
{
    /**
     * Register the application service.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('declension', function() {
            return new Declension;
        });
    }
}
