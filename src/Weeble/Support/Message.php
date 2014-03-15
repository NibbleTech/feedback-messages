<?php

namespace Weeble\Support;

class Message {
	protected $type;
	protected $message;

	function __construct($message, $type) {
		$this->message = $message;
		$this->type = $type;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getMessage()
	{
		return $this->message;
	}
}