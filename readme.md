## Feedback Messages support class

My start at getting all my agnostic stuff packaged up.

Isnt interfaced yet, tests? lol (later)

## Installation

In laravel add the following to your ```app.php```:
```
	'providers' => array(
		...
		...

		'Weeble\FeedbackServiceProvider'

	),

	'aliases' => array(
		...
		...

		'Feedback' => 'Weeble\Facades\Feedback'

	)
```