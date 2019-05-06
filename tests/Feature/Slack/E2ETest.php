<?php

namespace Tests\Feature;

use App\Models\Kudos;
use App\Models\User;
use App\Notifications\KudosNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class E2ETest extends TestCase
{
    use RefreshDatabase;

    protected function getSlackRequest($array = [])
    {
    	return array_merge([
			'token' => 'nMBVM6IGSpg5PbvNYSvMFJ8Y',
			'team_id' => 'T025CNFDY',
			'team_domain' => 'highsolutions',
			'channel_id' => 'GGMEMB6S0',
			'channel_name' => 'privategroup',
			'user_id' => 'U025D6EPH',
			'user_name' => 'adam',
			'command' => '/kudos',
			'text' => 'dla <@U025D6EPH|adam> <@U025D6EP1|adam2> za tę integrację :parrot: :) #zaangażowanie #rozwój',
			'response_url' => 'https://hooks.slack.com/commands/T025CNFDY/564793726912/hkg2uZGFID7E8iCb9AQDN6xv',
			'trigger_id' => '565542326082.2182763474.be2df160b5961c826afb28e52df97dc0',
    	], $array);
    }

    protected function assertWrongFormat($response)
    {
        $response->assertOk();
        $response->assertExactJson([
        	'response_type' => 'ephemeral',
        	'text' => 'Wiadomość jest źle sformatowana. Wyślij karteczkę jeszcze raz',
        	'attachments' => [
        		[
					'text' => 'np. dla @janusz za dużego deala #wyzwanie',
				]
			],
        ]);    	
    }

    /**
     * @test
     */
    public function proper_message()
    {
    	Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest());

        $response->assertOk();

        $kudos = Kudos::first();
        $this->assertEquals('tę integrację :parrot: :)', $kudos->message);
        $this->assertEquals('adam', $kudos->sender->username);
        $this->assertEquals('U025D6EPH', $kudos->sender->slack_id);
;
        tap($kudos->receivers[0], function ($receiver) {
	        $this->assertEquals('adam', $receiver->username);
	        $this->assertEquals('U025D6EPH', $receiver->slack_id);
        });

        tap($kudos->receivers[1], function ($receiver) {
	        $this->assertEquals('adam2', $receiver->username);
	        $this->assertEquals('U025D6EP1', $receiver->slack_id);
        });

        tap($kudos->values[0], function ($value) {
	        $this->assertEquals('zaangażowanie', $value->text);
        });

        tap($kudos->values[1], function ($value) {
	        $this->assertEquals('rozwój', $value->text);
        });

        Notification::assertSentTo($kudos->sender, KudosNotification::class, function ($notification) {
        	return $notification->message == '<@U025D6EPH|adam> daje e-karteczkę dla *<@U025D6EPH|adam> <@U025D6EP1|adam2>* _za tę integrację :parrot: :)_ `#zaangażowanie #rozwój`.';
        });
    }

    /**
     * @test
     */
    public function proper_message_without_values()
    {
    	Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
			'text' => 'dla <@U025D6EPH|adam> <@U025D6EP1|adam2> za tę integrację :parrot: :)',
		]));

        $response->assertOk();

        $kudos = Kudos::first();
        $this->assertEquals(0, $kudos->values()->count());

        Notification::assertSentTo($kudos->sender, KudosNotification::class, function ($notification) {
        	return $notification->message == '<@U025D6EPH|adam> daje e-karteczkę dla *<@U025D6EPH|adam> <@U025D6EP1|adam2>* _za tę integrację :parrot: :)_.';
        });
    }

    /**
     * @test
     */
    public function proper_message_with_one_receiver()
    {
    	Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
			'text' => 'dla <@U025D6EP1|adam2> za tę integrację :parrot: :)',
		]));

        $response->assertOk();

        $kudos = Kudos::first();
        $this->assertEquals(1, $kudos->receivers()->count());
        
        tap($kudos->receivers[0], function ($receiver) {
	        $this->assertEquals('adam2', $receiver->username);
	        $this->assertEquals('U025D6EP1', $receiver->slack_id);
        });

        Notification::assertSentTo($kudos->sender, KudosNotification::class, function ($notification) {
        	return $notification->message == '<@U025D6EPH|adam> daje e-karteczkę dla *<@U025D6EP1|adam2>* _za tę integrację :parrot: :)_.';
        });
    }

    /**
     * @test
     */
    public function proper_message_but_lost_emoji_at_the_end()
    {
        Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
            'text' => 'dla <@U025D6EP1|adam2> za tę integrację #rozwój :parrot: :)',
        ]));

        $response->assertOk();

        $kudos = Kudos::first();

        Notification::assertSentTo($kudos->sender, KudosNotification::class, function ($notification) {
            return $notification->message == '<@U025D6EPH|adam> daje e-karteczkę dla *<@U025D6EP1|adam2>* _za tę integrację :parrot: :)_ `#rozwój`.';
        });
    }

    /**
     * @test
     */
    public function proper_message_with_channel_in_description()
    {
        Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
            'text' => 'dla <@U025D6EP1|adam2> za pomoc na kanale <#CGS6231LQH|help> #zaangażowanie',
        ]));

        $response->assertOk();

        $kudos = Kudos::first();

        Notification::assertSentTo($kudos->sender, KudosNotification::class, function ($notification) {
            return $notification->message == '<@U025D6EPH|adam> daje e-karteczkę dla *<@U025D6EP1|adam2>* _za pomoc na kanale <#CGS6231LQH|help>_ `#zaangażowanie`.';
        });
    }

    /**
     * @test
     */
    public function proper_message_with_all_users()
    {
        Notification::fake();

        factory(User::class)->create([
            'username' => 'test1',
            'slack_id' => 'U123456',
        ]);

        factory(User::class)->create([
            'username' => 'test2',
            'slack_id' => 'U789012',
        ]);

        factory(User::class)->create([
            'username' => 'adam',
            'slack_id' => 'U025D6EPH',
        ]);

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
            'text' => 'dla @all za życzenia urodzinowe #zaangażowanie',
        ]));

        $response->assertOk();

        $kudos = Kudos::first();

        Notification::assertSentTo($kudos->sender, KudosNotification::class, function ($notification) {
            return $notification->message == '<@U025D6EPH|adam> daje e-karteczkę dla *<@U123456|test1> <@U789012|test2>* _za życzenia urodzinowe_ `#zaangażowanie`.';
        });
    }

    /**
     * @test
     */
    public function proper_message_with_two_keywords()
    {
        Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
            'text' => 'dla <@U025D6EPH|adam> za to i za to',
        ]));

        $response->assertOk();

        $kudos = Kudos::first();
        $this->assertEquals('to i za to', $kudos->message);

        Notification::assertSentTo($kudos->sender, KudosNotification::class, function ($notification) {
            return $notification->message == '<@U025D6EPH|adam> daje e-karteczkę dla *<@U025D6EPH|adam>* _za to i za to_.';
        });
    }

    /**
     * @test
     */
    public function inproper_message_because_no_receiver()
    {
    	Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
			'text' => 'dla Slackbota za tę integrację :parrot: :)',
		]));

        $this->assertWrongFormat($response);

        $this->assertEquals(0, Kudos::count());

        Notification::assertNothingSent();
    }

    /**
     * @test
     */
    public function inproper_message_because_wrong_format()
    {
    	Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
			'text' => 'blubry',
		]));

        $this->assertWrongFormat($response);

        $this->assertEquals(0, Kudos::count());

        Notification::assertNothingSent();
    }
    
}
