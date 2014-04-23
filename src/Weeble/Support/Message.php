<?php

namespace Weeble\Support;

class Message {
	protected $type;
	protected $message;

	function __construct($message, $type) {
		$this->message = $message;
		$this->type = $type;
		$this->validate();
	}

	public function getType()
	{
		return $this->type;
	}

	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Validate data
	 *
	 * @return void
	 * @author 
	 **/
	private function validate()
	{
		if( ! is_string($this->message) ) throw new \InvalidArgumentException("Message is not a string");

		if( ! is_string($this->type) ) throw new \InvalidArgumentException("Message is not a string");
		
	}
}