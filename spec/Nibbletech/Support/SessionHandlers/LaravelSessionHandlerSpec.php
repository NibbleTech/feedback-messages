<?php

namespace spec\Nibbletech\Support\SessionHandlers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LaravelSessionHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nibbletech\Support\SessionHandlers\LaravelSessionHandler');
    }

}
