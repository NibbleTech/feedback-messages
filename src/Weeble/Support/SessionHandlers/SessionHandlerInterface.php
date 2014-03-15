<?php

namespace Weeble\Support\SessionHandlers;

interface SessionHandlerInterface {
	
	/**
	 * Get data by 'key' from the session
	 *
	 * @param $key session key to retreive
	 * @return array
	 **/
	public function get($key);
	
	/**
	 * Get data by 'key' from the session
	 *
	 * @param $key session key to store
	 * @param $data data to save to session
	 **/
	public function flash($key, $data);
}