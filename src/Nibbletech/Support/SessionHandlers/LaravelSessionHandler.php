<?php

namespace Nibbletech\Support\SessionHandlers;

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

	public function forget($key)
	{
		\Session::forget($key);
	}
}
