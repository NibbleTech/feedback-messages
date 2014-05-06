<?php
namespace Weeble\Support;

class MessageFactory {

	public function create($message, $type, $group)
	{
		return new Message($message, $type, $group);
	}

}