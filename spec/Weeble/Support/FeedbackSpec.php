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
	function let($session)
	{
		$session->beADoubleOf('Weeble\Support\SessionHandlers\LaravelSessionHandler');
		$this->beConstructedWith($session);
	}
    public function getMatchers()
    {
        return [
            'beArrayOfAmount' => function($subject, $number) {
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
        $session->get('feedbackMessages')->willReturn([]);
        $this->all()->shouldReturn([]);

    }

    function it_gets_empty_array_when_session_is_empty($session)
    {
        $session->get('feedbackMessages')->willReturn([]);
        $this->all()->shouldReturn([]);
    }

    function it_adds_an_error_message(){
        // Add 1 message
        $this->add('message', 'type', 'channel');


        $this->all()->shouldHaveCount(1);

        // Check message is returned in getting errors
        $this->byType('type')->shouldHaveCount(1);
        $this->get('channel')->shouldHaveCount(1);
    }

    function it_adds_an_info_message(){
        // Add 1 message
        $this->info($this->testMessages[0]['message']);


        $this->all()->shouldHaveCount(1);

        // Check message is returned in getting errors
        $this->byType('info')->shouldHaveCount(1);
    }

    function it_adds_an_success_message(){
        // Add 1 message
        $this->success($this->testMessages[0]['message']);


        $this->all()->shouldHaveCount(1);

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
}
