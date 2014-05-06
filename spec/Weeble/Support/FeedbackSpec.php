<?php

namespace spec\Weeble\Support;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FeedbackSpec extends ObjectBehavior
{
    private $testMessages = [
        'error' => [
            ['message' => 'Test error message 1',
            'type' => 'error'],
            ['message' => 'Test error message 2',
            'type' => 'error'],
            ['message' => 'Test error message 3',
            'type' => 'error']
        ],
    ];

    private $sessionKey = "feedbackMessages";
    private $oldSuffix = "old";
    private $newSuffix = "new";
	function let($session, $messageFactory, $message)
	{
        $session->beADoubleOf('Weeble\Support\SessionHandlers\LaravelSessionHandler');
        $messageFactory->beADoubleOf('Weeble\Support\MessageFactory');
		$message->beADoubleOf('Weeble\Support\Message');
		$this->beConstructedWith($session, $messageFactory);
	}
    public function getMatchers()
    {
        return [
            'beArrayOfCount' => function($subject, $number) {
                return count($subject) == $number;
            },
        ];
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Weeble\Support\Feedback');
    }

    function it_should_start_with_an_empty_feedback_array($session)
    {
        $session->get(Argument::any())->willReturn([]);
        $session->get(Argument::any())->willReturn([]);

        $this->all()->shouldHaveCount(0);
    }

    function it_adds_an_error_message($session, $messageFactory, $message){
        // Mock message creation
        $messageFactory->create('Error test message', 'error', 'global')->willReturn($message);
        $message->setTypeAlias(Argument::type('string'))->shouldBeCalled();
        // Mock session calls
        $session->flash('feedbackMessages.new', Argument::any())->shouldBeCalled();
        $session->get('feedbackMessages.old')->willReturn([]);
        $session->get('feedbackMessages.new')->willReturn([]);

        // Add 1 message
        $this->error('Error test message');

        // Check message is returned in getting errors
        $this->all()->shouldHaveCount(1);
    }

    function it_adds_an_info_message($session, $messageFactory, $message){
        // Mock message creation
        $messageFactory->create('Info test message', 'info', 'global')->willReturn($message);
        $message->setTypeAlias(Argument::type('string'))->shouldBeCalled();
        // Mock session calls
        $session->flash('feedbackMessages.new', Argument::any())->shouldBeCalled();
        $session->get('feedbackMessages.old')->willReturn([]);
        $session->get('feedbackMessages.new')->willReturn([]);

        // Add 1 message
        $this->info('Info test message');

        // Check message is returned in getting errors
        $this->all()->shouldHaveCount(1);
    }

    function it_adds_an_success_message($session, $messageFactory, $message){
        // Mock message creation
        $messageFactory->create('Success test message', 'success', 'global')->willReturn($message);
        $message->setTypeAlias(Argument::type('string'))->shouldBeCalled();
        // Mock session calls
        $session->flash('feedbackMessages.new', Argument::any())->shouldBeCalled();
        $session->get('feedbackMessages.old')->willReturn([]);
        $session->get('feedbackMessages.new')->willReturn([]);

        // Add 1 message
        $this->success('Success test message');

        // Check message is returned in getting errors
        $this->all()->shouldHaveCount(1);
    }

    function it_can_get_messages_by_group($session, $messageFactory, $message){
        // Mock session calls
        $session->flash('feedbackMessages.new', Argument::any())->shouldBeCalled();
        $session->get('feedbackMessages.old')->willReturn([]);
        $session->get('feedbackMessages.new')->willReturn([]);

        // Mock message creation
        $message->setTypeAlias(Argument::type('string'))->shouldBeCalled();
        $messageFactory->create('Success test message', 'success', 'group1')->willReturn($message);


        // mock emssage creation
        $messageFactory->create('Success test message', 'success', 'group1')->willReturn($message);

        // Create 2nd set of group messages
        $this->success('Success test message', 'group1');
        $this->success('Success test message', 'group1');

        // Mock mess calls in the get() method
        $message->getGroup()->willReturn('group1');

        // Check message is returned in getting errors
        $this->all()->shouldHaveCount(2);
        $this->get('group1')->shouldHaveCount(2);
        $this->get('group2')->shouldHaveCount(0);
    }

    function it_gets_empty_array_when_none_existant_group($session, $messageFactory, $message){
        // Mock session calls
        $session->get('feedbackMessages.old')->willReturn([]);
        $session->get('feedbackMessages.new')->willReturn([]);

        $this->get('non-existant-group')->shouldReturn([]);
    }

    function it_can_get_messages_by_type($session, $messageFactory, $message){
        // Mock session calls
        $session->flash('feedbackMessages.new', Argument::any())->shouldBeCalled();
        $session->get('feedbackMessages.old')->willReturn([]);
        $session->get('feedbackMessages.new')->willReturn([]);

        // Mock message creation
        $message->setTypeAlias(Argument::type('string'))->shouldBeCalled();
        $messageFactory->create('Success test message', 'success', 'global')->willReturn($message);

        // Create 2nd set of group messages
        $this->success('Success test message');
        $this->success('Success test message');

        // Mock mess calls in the get() method
        $message->getType()->willReturn('success');

        // Check message is returned in getting errors
        $this->all()->shouldHaveCount(2);
        $this->byType('success')->shouldHaveCount(2);
    }

    function it_gets_empty_array_when_none_by_type($session, $message){
        // Mock session calls
        $session->get('feedbackMessages.old')->willReturn([]);
        $session->get('feedbackMessages.new')->willReturn([]);

        // Mock mess calls in the get() method
        $message->getType()->willReturn('success');

        // Check message is returned in getting errors
        $this->byType('none-existant-type')->shouldHaveCount(0);
    }

    function it_can_merge_an_array_of_messages($session, $messageFactory, $message){
        // Mock session calls
        $session->flash('feedbackMessages.new', Argument::any())->shouldBeCalled();
        $session->get('feedbackMessages.old')->willReturn([]);
        $session->get('feedbackMessages.new')->willReturn([]);

        $messages = [
            "Message 1", "Message 2", "Message 3"
        ];
        $messages2 = [
            "Message 4", "Message 5", "Message 6", "Message 7", "Message 8"
        ];

        // Mock message creation
        $message->setTypeAlias(Argument::type('string'))->shouldBeCalled();
        $messageFactory->create(Argument::type('string'), 'success', 'channel')->willReturn($message);

        $this->merge($messages, 'success', 'channel');

        $this->all()->shouldHaveCount(3);

        $this->merge($messages2, 'success', 'channel');

        $this->all()->shouldHaveCount(8);
    }
}
