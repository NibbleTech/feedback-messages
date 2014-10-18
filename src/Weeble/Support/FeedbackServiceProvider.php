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
		$this->app->bind('Weeble\Support\SessionHandlers\SessionHandlerInterface', 'Weeble\Support\SessionHandlers\LaravelSessionHandler');
		$this->app->singleton('view.feedback', 'Weeble\Support\Feedback');
	}

	public function boot()
	{
		$this->package('weeble/support/feedback', 'weeble/support/feedback', __DIR__ . '/../../');

		$this->app['view.feedback']->regenerateSession();
		$this->app['view.feedback']->setTypeAlias($this->app['config']['weeble/support/feedback::types']);
	}

    public function provides()
    {
        return array('view.feedback');
    }
}
