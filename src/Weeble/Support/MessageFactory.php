<?php
namespace Weeble\Support;

class MessageFactory {

	public function create($message, $type)
	{
		return new Message($message, $type);
	}

}