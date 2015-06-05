## Feedback Messages support class

A package to make it really easy to pass and retrieve messages within the session.

## Installation

### Composer

Add the package to your `composer.json` file:
```
"nibbletech/feedback-messages": "dev-master"
```

### Laravel
In laravel add the following to your ```app.php```:
```php
	'providers' => array(
		...
		...

		'Nibbletech\Support\FeedbackServiceProvider'

	),

	'aliases' => array(
		...
		...

		'Feedback' => 'Nibbletech\Support\Facades\Feedback'

	)
```

## Usage

You add messages to the session by passing a message, the message type (e.g 'error') and setting an optional channel (defaults to 'global').

To add a message:
```php
Feedback::add('You found a great package!', 'success', 'custom-channel')
```

Feedback uses magic methods to make it really easy to add messages of a certain type, just call the type you want as the function and the package handles the rest.
These take a message string and an optional 2nd paramater which allows you to set the channel the message goes into, otherwise it defaults to the `global` channel:

```php
Feedback::info('message', 'optional-channel')
```
```php
Feedback::error('message')
```
```php
Feedback::success('message')
```


## Retreiving Messages

### All as original structure

To retrieve an array of all messages just call:
```php
Feedback::all();
```

### Specific Channel

You can retrieve an array of all the messages for a specific channel:
```php
Feedback::get('channel-name');
```

### All messages by type

This returns an array of all messages of a specfic type, regardless of their channel
```php
Feedback::byType('error');
```