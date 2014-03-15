<?php

namespace Weeble\Support;

use Illuminate\Support\ServiceProvider;

class FeedbackServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['feedback'] = $this->app->share(function($app)
		{
			// Return App::make not new Class because using App::make auto resolves dependencies.
			return \App::make('Weeble\Support\Feedback', [
				new SessionHandlers\LaravelSessionHandler
			]);
		});
	}
}
