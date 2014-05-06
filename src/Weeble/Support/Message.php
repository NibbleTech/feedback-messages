<?php

namespace Weeble\Support;

class Message {
	protected $type;
	protected $typeAlias;
	protected $message;
	protected $group;

	function __construct($message, $type, $group) {
		$this->message = $message;
		$this->type = $type;
		$this->typeAlias = $type;
		$this->group = $group;
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

	public function getTypeAlias()
	{
		return $this->typeAlias;
	}

	public function getGroup()
	{
		return $this->group;
	}

	public function setTypeAlias($alias)
	{
		$this->typeAlias = $alias;
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