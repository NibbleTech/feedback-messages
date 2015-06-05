<?php

namespace spec\Nibbletech\Support;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MessageSpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith("message", "type", "group");
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Nibbletech\Support\Message');
    }

    function it_gets_the_message()
    {
    	$this->getMessage()->shouldReturn("message");
    }

    function it_gets_the_type()
    {
        $this->getType()->shouldReturn("type");
    }
    function it_gets_the_group()
    {
        $this->getGroup()->shouldReturn("group");
    }
    function it_can_set_and_get_the_type_alias()
    {
        $this->getTypeAlias()->shouldReturn("type");
        $this->setTypeAlias("type alias");
        $this->getTypeAlias()->shouldReturn("type alias");
    }
}
