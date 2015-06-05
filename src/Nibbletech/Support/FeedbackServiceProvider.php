<?php

namespace Nibbletech\Support;

use Illuminate\Support\ServiceProvider;

class FeedbackServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . "/../../config/config.php",
			'nibbletech/support/feedback'
		);

		$this->app->bind('Nibbletech\Support\SessionHandlers\SessionHandlerInterface', 'Nibbletech\Support\SessionHandlers\LaravelSessionHandler');
		$this->app->singleton('feedback', 'Nibbletech\Support\Feedback');
	}

	public function boot()
	{
		$this->app['feedback']->setTypeAlias($this->app['config']['nibbletech/support/feedback']['types']);
	}

    public function provides()
    {
        return array('feedback');
    }
}
