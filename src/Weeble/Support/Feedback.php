<?php

namespace Weeble\Support;


class Feedback {
	
	private $feedback = [];

	private $defaultChannel = 'global';

	private $sessionKey = 'feedbackMessages';

	private $oldSuffix = 'old';
	private $newSuffix = 'new';

	private $typeAlias = [
		'success' => 'success',
		'info' => 'info',
		'error' => 'error',
		'warning' => 'warning'
	];

	private $session;

	function __construct(SessionHandlers\SessionHandlerInterface $sessionHandler, MessageFactory $messageFactory) {
		$this->session = $sessionHandler;
		$this->feedback = $this->getSessionData();
		$this->messageFactory = $messageFactory;
	}


	/**
	 * Magic method for adding messages of $name type
	 *
	 * @return void
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
    public function __call($name, $arguments)
    {
    	if( isset($arguments[1]) && is_string($arguments[1]) ){
			$this->add($arguments[0], $name, $arguments[1]);
		}else{
			$this->add($arguments[0], $name, $this->defaultChannel);
		}
    }

	/**
	 * Add the feedback message to the array
	 *
	 * @return void
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function add($message, $type, $channel)
	{
		if(is_null($channel)) $channel = $this->defaultChannel;
		
		$message = $this->messageFactory->create($message, $type, $channel);

		$message->setTypeAlias( $this->typeAlias[$type] );
		
		array_push($this->feedback['new'], $message);
		
		$this->setSessionData();
	}

	/**
	 * Get named section
	 *
	 * @return array
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function get($channel){
		$feedback = $this->all();
		// If there are no feedback messages return empty array
		if( empty( $feedback ) ) return [];

		$get = [];
		foreach ($feedback as $message) {
			if( $message->getGroup() == $channel){
				$get[] = $message;
			}
		}
		return $get;
	}

	/**
	 * Get all feedback messages of a certain type regardless of group
	 *
	 * @return array
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function byType($type){

		$get = [];
		foreach ($this->all() as $message) {
			if( $message->getType() == $type ){
				$get[] = $message;
			}
		}
		return $get;
	}

	public function all()
	{
		return array_merge( $this->feedback[ $this->oldSuffix ] , $this->feedback[ $this->newSuffix ] );
	}

	public function merge(array $messages, $type, $channel = null)
	{
		if( is_null($channel) ) $channel = $this->defaultChannel;

		foreach ($messages as $message) {
			if( is_array($message) ){
				$this->merge($message, $type, $channel);
			}else{
				$this->add($message, $type, $channel);
			}
		}
	}

	private function setSessionData()
	{
		$this->session->flash($this->sessionKey . '.' . $this->newSuffix, $this->feedback['new']);
		// $this->session->flash($this->sessionKey . '.' . $this->newSuffix, $this->feedback['new']);
	}

	private function getSessionData()
	{
		return [
			$this->oldSuffix => $this->session->get($this->sessionKey . '.' . $this->oldSuffix),
			$this->newSuffix => $this->session->get($this->sessionKey . '.' . $this->newSuffix)
		];

	}

	public function regenerateSession()
	{
		$this->session->flash(
			$this->sessionKey . '.' . $this->oldSuffix,
			$this->session->get( $this->sessionKey . '.' . $this->newSuffix )
		);

		$this->session->forget($this->sessionKey . '.' . $this->newSuffix);

		$this->feedback = $this->getSessionData();
	}

	public function throwOnBadChannel($channel)
	{
		if( ! is_string( $channel ) ) throw new \InvalidArgumentException("Channel parameter must be a string");
	}

	public function setTypeAlias($names)
	{
		$this->typeAlias = array_merge($this->typeAlias, $names);
	}

}
