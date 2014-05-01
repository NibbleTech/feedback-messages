<?php

namespace Weeble\Support;

class Feedback {
	
	private $feedback = [];

	private $defaultName = 'global';

	private $sessionKey = 'feedbackMessages';

	private $oldSuffix = 'old';
	private $newSuffix = 'new';

	private $cssAlias = [
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
	 * Add an error feedback message
	 *
	 * @return void
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function error($message, $channel = null)
	{
		$this->add($message, 'error', $channel);
	}

	/**
	 * Add an informational feedback message
	 *
	 * @return void
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function info($message, $channel = null)
	{
		$this->add($message, 'info', $channel);
	}

	/**
	 * Add success feedback message
	 *
	 * @return void
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function success($message, $channel = null)
	{
		$this->add($message, 'success', $channel);
	}

	/**
	 * Add warning feedback message
	 *
	 * @return void
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function warning($message, $channel = null)
	{
		$this->add($message, 'warning', $channel);
	}

	/**
	 * Add the feedback message to the array
	 *
	 * @return void
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function add($message, $type, $channel)
	{
		if(is_null($channel) || empty($channel)) $channel = $this->defaultName;

		$this->throwOnBadChannel($channel);
		
		$message = $this->messageFactory->create($message, $type);

		$message->setTypeAlias( $this->typeAlias[$type] );

		if( ! isset( $this->feedback['new'][$channel] ) ) $this->feedback['new'][$channel] = [];
		
		array_push($this->feedback['new'][$channel], $message);
		
		$this->setSessionData();
	}

	/**
	 * Get named section
	 *
	 * @return array
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function get($group){
		$feedback = $this->all();
		// If there are no feedback messages return empty array
		if( empty( $feedback ) ) return $feedback;
		// If named section is set return it, otherwise return empty array to not break foreach's in app
		if( isset( $feedback[$group] ) ) return $feedback[$group];

		return [];
	}

	/**
	 * Get all feedback messages of a certain type regardless of group
	 *
	 * @return array
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function byType($type){

		$feedback = $this->all();

		if( empty( $feedback ) ) return [];

		$allMessages = [];
		foreach( $feedback as $group){
			foreach ($group as $groupMessage) {

				if($groupMessage->getType() == $type){
					$allMessages[] = $groupMessage;
				}

				
			}
		}
		return $allMessages;
	}

	public function all()
	{
		return array_merge_recursive( $this->feedback[ $this->oldSuffix ] , $this->feedback[ $this->newSuffix ] );
	}

	public function merge(array $messages, $type = 'error', $channel = null)
	{
		if( is_null($channel) ) $channel = $this->defaultName;
		foreach ($messages as $message) {
			if( is_array($message) ){
				$this->merge($message);
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

	public function setNames($names)
	{
		array_merge($this->typeAlias, $names);
	}

}
