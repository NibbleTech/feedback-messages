<?php

namespace spec\Weeble\Support;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FeedbackSpec extends ObjectBehavior
{
    private $testMessages = [
        ['message' => 'Test message 1',
        'type' => 'error'],
        ['message' => 'Test message 2',
        'type' => 'error'],
        ['message' => 'Test message 3',
        'type' => 'success'],
        ['message' => 'Test message 4',
        'type' => 'success'],
        ['message' => 'Test message 5',
        'type' => 'info'],
    ];

    private $sessionKey = "feedbackMessages";
    private $oldSuffix = "old";
    private $newSuffix = "new";
	function let($session, $messageFactory)
	{
        $session->beADoubleOf('Weeble\Support\SessionHandlers\LaravelSessionHandler');
		$messageFactory->beADoubleOf('Weeble\Support\MessageFactory');
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
        $session->get($this->sessionKey . '.' . $this->oldSuffix)->willReturn([]);
        $session->get($this->sessionKey . '.' . $this->newSuffix)->willReturn([]);

        $this->all()->shouldHaveCount(0);
    }

    function it_adds_an_error_message($session, $messageFactory, \Weeble\Support\Message $message){

        $messageFactory->create('error message', 'error')->willReturn($message);

        $session->flash($this->sessionKey . '.' . $this->newSuffix, Argument::any())->shouldBeCalled();

        $session->get($this->sessionKey . '.' . $this->oldSuffix)->willReturn([]);
        $session->get($this->sessionKey . '.' . $this->newSuffix)->willReturn(
            ['global' => [
                $message
            ]]
        );

        // Add 1 message
        $this->error('error message');

        // Check message is returned in getting errors
        $this->byType('error')->shouldHaveCount(1);

    }

    function it_adds_an_info_message(){
        // Add 1 message
        $this->info($this->testMessages[0]['message']);

        // Check message is returned in getting errors
        $this->byType('info')->shouldHaveCount(1);
    }

    function it_adds_an_success_message(){
        // Add 1 message
        $this->success($this->testMessages[0]['message']);

        // Check message is returned in getting errors
        $this->byType('success')->shouldHaveCount(1);
    }

    function it_can_add_messages_to_different_groups(){
        // Add 1 message
        $this->success($this->testMessages[0]['message'], 'group1');
        $this->success($this->testMessages[0]['message'], 'group2');
        $this->success($this->testMessages[0]['message'], 'group3');

        $this->all()->shouldHaveCount(3);
    }

    function it_can_get_messages_by_group(){
        // Add 1 message
        $this->success($this->testMessages[0]['message'], 'group1');
        $this->success($this->testMessages[0]['message'], 'group1');
        $this->success($this->testMessages[0]['message'], 'group1');
        $this->success($this->testMessages[0]['message'], 'group2');
        $this->success($this->testMessages[0]['message'], 'group2');

        $this->get('group1')->shouldHaveCount(3);
        $this->get('group2')->shouldHaveCount(2);
    }

    function it_gets_empty_array_when_none_by_group($session){

        $session->get('feedbackMessages')->willReturn([]);

        $this->get('group1')->shouldReturn([]);
        $this->get('group2')->shouldReturn([]);
    }

    function it_can_get_messages_by_type(){
        // Add 1 message
        $this->success($this->testMessages[0]['message']);
        $this->success($this->testMessages[0]['message']);
        $this->success($this->testMessages[0]['message']);
        $this->info($this->testMessages[0]['message']);
        $this->error($this->testMessages[0]['message']);

        $this->byType('success')->shouldHaveCount(3);
    }

    function it_gets_empty_array_when_none_by_type($session){
        
        $session->get('feedbackMessages')->willReturn([]);

        $this->byType('success')->shouldReturn([]);
        $this->byType('info')->shouldReturn([]);
        $this->byType('error')->shouldReturn([]);
    }

    function it_can_merge_an_array_of_messages(){
        $messages = [
            "Message 1", "Message 2", "Message 3"
        ];
        $messages2 = [
            "Message 6", "Message 7", "Message 8", "Message 9", "Message 10"
        ];

        // Cant run merge() twice in a row for the same group? the 2nd one doesnt get added tp array
        $this->merge( $messages , 'success', 'channel1');

        $this->byType('success')->shouldHaveCount(3);
        $this->get('channel1')->shouldHaveCount(3);

        $this->merge( $messages2 , 'error', 'channel2');

        $this->byType('error')->shouldHaveCount(5);
        $this->get('channel2')->shouldHaveCount(5);

        $this->all()->shouldHaveCount(2);
    }

    function it_throws_exception_when_non_string_channel_is_passed()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringAdd('message', 'error', ['not a string channel']);
    }
}
