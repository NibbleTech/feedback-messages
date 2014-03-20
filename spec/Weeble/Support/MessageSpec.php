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

    function it_throws_exception_when_non_string_message_is_passed()
    {
        $this->shouldThrow('\InvalidArgumentException')->during__construct(['not a string'], 'error');
    }
    function it_throws_exception_when_non_string_type_is_passed()
    {
        $this->shouldThrow('\InvalidArgumentException')->during__construct('message', ['not a string error']);
    }
}
