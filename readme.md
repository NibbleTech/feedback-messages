## Feedback Messages support class

A package to make it really easy to pass and retrieve messages within the session.

## Installation

In laravel add the following to your ```app.php```:
```
	'providers' => array(
		...
		...

		'Weeble\Support\FeedbackServiceProvider'

	),

	'aliases' => array(
		...
		...

		'Feedback' => 'Weeble\Support\Facades\Feedback'

	)
```

## Usage

Feedback supports 3 types of messages so far `info`, `error` and `success` called respectively as:
```
Feedback::info()
```
```
Feedback::error()
```
```
Feedback::success()
```

TODO: there will be a standard method call for adding messages, these are just the helpers I use at the moment.

### Structure

The messages are stored in an associative array by the channel they are in. The default channel is `global`, you can add messages to whatever channel you wish by passing it as a paramater:
```
Feedback::success('Well done!', 'my-custom-channel');
```

The core structure looks like this:
```
'global' => [Message, ...],
'other-channel' => [Message, ...]
```


## Retreiving Messages

### All as original structure

To retrieve the core array, retaining the original structure, just call:
```
Feedback::all();
```

### Specific Channel

You can retrieve an array of all the messages for a specific channel:
```
Feedback::get('channel-name');
```

### All messages by type

This returns an array of all messages of a specfic type, regardless of their channel
```
Feedback::byType('error');
```