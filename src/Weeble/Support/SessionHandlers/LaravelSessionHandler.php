<?php

namespace Weeble\Support\SessionHandlers;

class LaravelSessionHandler implements SessionHandlerInterface
{
	public function get($key)
	{
		return \Session::get($key, []);
	}

	public function flash($key, $data)
	{
		\Session::flash($key, $data);
	}
}
