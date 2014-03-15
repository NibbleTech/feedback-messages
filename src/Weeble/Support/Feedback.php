<?php

namespace Weeble\Support;

class Feedback {
	
	private $feedback = [];

	private $defaultName = 'global';

	private $sessionKey = 'feedbackMessages';

	private $session;

	function __construct(SessionHandlers\SessionHandlerInterface $sessionHandler) {
		$this->session = $sessionHandler;
		$this->feedback = $this->session->get( $this->sessionKey );
	}

	/**
	 * Add an error feedback message
	 *
	 * @return void
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function error($message, $name = null)
	{
		if(is_null($name) || empty($name)) $name = $this->defaultName;

		$this->setFeedback($name, 'error', $message);
	}

	/**
	 * Add an informational feedback message
	 *
	 * @return void
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function info($message, $name = null)
	{
		if(is_null($name) || empty($name)) $name = $this->defaultName;

		$this->setFeedback($name, 'info', $message);
	}

	/**
	 * Add success feedback message
	 *
	 * @return void
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function success($message, $name = null)
	{
		if(is_null($name) || empty($name)) $name = $this->defaultName;

		$this->setFeedback($name, 'success', $message);
	}

	/**
	 * Add the feedback message to the array
	 *
	 * @return void
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	private function setFeedback($name, $type, $message)
	{
		$message = new Message($message, $type);

		if( ! isset( $this->feedback[$name] ) ) $this->feedback[$name] = [];
		
		array_push($this->feedback[$name], $message);
		
		$this->setSessionData();
	}

	/**
	 * Get named section
	 *
	 * @return array
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function get($group){
		// If there are no feedback messages return empty array
		if(empty($this->feedback)) return $this->feedback;
		// If named section is set return it, otherwise return empty array to not break foreach's in app
		if(isset($this->feedback[$group])) return $this->feedback[$group];

		return [];
	}

	/**
	 * Get all feedback messages of a certain type regardless of group
	 *
	 * @return array
	 * @author Shaun Walker (shaunwalker@nibbletech.co.uk)
	 **/
	public function byType($type){

		if(empty($this->feedback)) return $this->feedback;

		$allMessages = [];
		foreach($this->feedback as $group){
			foreach ($group as $groupMessage) {

				if($groupMessage->getType() != $type) break;

				$allMessages[] = $groupMessage;
			}
		}
		return $allMessages;
	}

	public function all()
	{
		return $this->feedback;
	}

	public function merge(array $messages, $type = 'error', $name = null)
	{
		if( is_null($name) ) $name = $this->defaultName;
		foreach ($messages as $message) {
			if( is_array($message) ){
				$this->merge($message);
			}else{
				$this->setFeedback($name, $type, $message);
			}
		}
	}

	private function setSessionData()
	{
		$this->session->flash('feedbackMessages', $this->feedback);
	}

	private function getSessionData()
	{
		return $this->session->get('feedbackMessages');
	}
}
