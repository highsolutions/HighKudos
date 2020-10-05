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
			'command' => '/improv',
			'text' => 'poprawiony przecinek na stronie :parrot: :) #zaangażowanie #rozwój',
			'response_url' => 'https://hooks.slack.com/commands/T025CNFDY/564793726912/hkg2uZGFID7E8iCb9AQDN6xv',
			'trigger_id' => '565542326082.2182763474.be2df160b5961c826afb28e52df97dc0',
    	], $array);
    }

    protected function assertWrongFormat($response)
    {
        $response->assertOk();
        $response->assertExactJson([
        	'response_type' => 'ephemeral',
        	'text' => 'Wiadomość jest źle sformatowana. Wyślij improva jeszcze raz',
        	'attachments' => [
        		[
					'text' => 'np. zdobyłem deala za 100k #wyzwanie',
				]
			],
        ]);    	
    }

    /**
     * @test
     */
    public function proper_message()
    {
        $this->withoutExceptionHandling();

    	Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest());

        $response->assertOk();

        $kudos = Kudos::first();
        $this->assertEquals('poprawiony przecinek na stronie :parrot: :)', $kudos->message);
        $this->assertEquals('adam', $kudos->sender->username);
        $this->assertEquals('U025D6EPH', $kudos->sender->slack_id);

        tap($kudos->values[0], function ($value) {
	        $this->assertEquals('zaangażowanie', $value->text);
        });

        tap($kudos->values[1], function ($value) {
	        $this->assertEquals('rozwój', $value->text);
        });

        Notification::assertSentTo($kudos->sender, KudosNotification::class, function ($notification) {
        	return $notification->message == '<@U025D6EPH|adam> wykonał/-a improva _poprawiony przecinek na stronie :parrot: :)_ `#zaangażowanie #rozwój`.';
        });
    }

    /**
     * @test
     */
    public function proper_message_without_values()
    {
        $this->withoutExceptionHandling();
        
    	Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
			'text' => 'poprawiony przecinek na stronie :parrot: :)',
		]));

        $response->assertOk();

        $kudos = Kudos::first();
        $this->assertEquals(0, $kudos->values()->count());

        Notification::assertSentTo($kudos->sender, KudosNotification::class, function ($notification) {
        	return $notification->message == '<@U025D6EPH|adam> wykonał/-a improva _poprawiony przecinek na stronie :parrot: :)_.';
        });
    }

    /**
     * @test
     */
    public function proper_message_but_lost_emoji_at_the_end()
    {
        Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
            'text' => 'poprawiony przecinek na stronie :parrot: :)',
        ]));

        $response->assertOk();

        $kudos = Kudos::first();

        Notification::assertSentTo($kudos->sender, KudosNotification::class, function ($notification) {
            return $notification->message == '<@U025D6EPH|adam> wykonał/-a improva _poprawiony przecinek na stronie :parrot: :)_.';
        });
    }

    /**
     * @test
     */
    public function proper_message_with_channel_in_description()
    {
        Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
            'text' => 'utworzenie kanału <#CGS6231LQH|help> #zaangażowanie',
        ]));

        $response->assertOk();

        $kudos = Kudos::first();

        Notification::assertSentTo($kudos->sender, KudosNotification::class, function ($notification) {
            return $notification->message == '<@U025D6EPH|adam> wykonał/-a improva _utworzenie kanału <#CGS6231LQH|help>_ `#zaangażowanie`.';
        });
    }

    /**
     * @test
     */
    public function improv_proper_message_no_receiver()
    {
    	Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
			'text' => 'dla Slackbota za tę integrację :parrot: :)',
		]));

        $response->assertOk();
    }

    /**
     * @test
     */
    public function improv_proper_message_wrong_format()
    {
    	Notification::fake();

        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
			'text' => 'blubry',
		]));

        $response->assertOk();
    }
    
}
