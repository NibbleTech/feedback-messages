<?php

namespace spec\Weeble\Support\SessionHandlers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LaravelSessionHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Weeble\Support\SessionHandlers\LaravelSessionHandler');
    }

}
