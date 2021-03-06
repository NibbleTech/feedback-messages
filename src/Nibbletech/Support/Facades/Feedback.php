<?php

namespace Nibbletech\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Feedback extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'feedback'; }
}