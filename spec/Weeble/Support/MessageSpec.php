<?php

namespace spec\Weeble\Support;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MessageSpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith("message", "type");
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Weeble\Support\Message');
    }

    function it_gets_the_message()
    {
    	$this->getMessage()->shouldReturn("message");
    }

    function it_gets_the_type()
    {
    	$this->getType()->shouldReturn("type");
    }
}
