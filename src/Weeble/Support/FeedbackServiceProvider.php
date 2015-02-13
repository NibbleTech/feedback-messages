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
		$this->mergeConfigFrom(
			__DIR__ . "/../../config/config.php",
			'weeble/support/feedback'
		);

		$this->app->bind('Weeble\Support\SessionHandlers\SessionHandlerInterface', 'Weeble\Support\SessionHandlers\LaravelSessionHandler');
		$this->app->singleton('view.feedback', 'Weeble\Support\Feedback');
	}

	public function boot()
	{
		$this->app['view.feedback']->regenerateSession();
		$this->app['view.feedback']->setTypeAlias($this->app['config']['weeble/support/feedback']['types']);
	}

    public function provides()
    {
        return array('view.feedback');
    }
}
