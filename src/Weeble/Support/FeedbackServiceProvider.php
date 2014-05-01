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

	public function boot()
	{
		$this->package('weeble/support/feedback', 'weeble/support/feedback', __DIR__ . '/../../');

		$this->app['feedback']->regenerateSession();
		$this->app['feedback']->setTypeAlias($this->app['config']['weeble/support/feedback::types']);
	}

    public function provides()
    {
        return array('feedback');
    }
}
