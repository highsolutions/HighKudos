<?php

namespace Tests\Feature;

use App\Models\Kudos;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        	'message' => 'Wiadomość jest źle sformatowana. Wyślij kudosa jeszcze raz',
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
        $response = $this->post('/api/slack/fetch', $this->getSlackRequest());

        $response->assertOk();
        $response->assertExactJson([
        	'response_type' => 'in_channel',
        	'message' => 'dla @adam @adam2 za tę integrację :parrot: :) #zaangażowanie #rozwój od @adam',
        	'attachments' => [],
        ]);

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
    }

    /**
     * @test
     */
    public function proper_message_without_values()
    {
        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
			'text' => 'dla <@U025D6EPH|adam> <@U025D6EP1|adam2> za tę integrację :parrot: :)',
		]));

        $response->assertOk();
        $response->assertExactJson([
        	'response_type' => 'in_channel',
        	'message' => 'dla @adam @adam2 za tę integrację :parrot: :) od @adam',
        	'attachments' => [],
        ]);

        $kudos = Kudos::first();
        $this->assertEquals(0, $kudos->values()->count());
    }

    /**
     * @test
     */
    public function proper_message_with_one_receiver()
    {
        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
			'text' => 'dla <@U025D6EP1|adam2> za tę integrację :parrot: :)',
		]));

        $response->assertOk();
        $response->assertExactJson([
        	'response_type' => 'in_channel',
        	'message' => 'dla @adam2 za tę integrację :parrot: :) od @adam',
        	'attachments' => [],
        ]);

        $kudos = Kudos::first();
        $this->assertEquals(1, $kudos->receivers()->count());
        
        tap($kudos->receivers[0], function ($receiver) {
	        $this->assertEquals('adam2', $receiver->username);
	        $this->assertEquals('U025D6EP1', $receiver->slack_id);
        });
    }

    /**
     * @test
     */
    public function inproper_message_because_no_receiver()
    {
        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
			'text' => 'dla Slackbota za tę integrację :parrot: :)',
		]));

        $this->assertWrongFormat($response);

        $this->assertEquals(0, Kudos::count());
    }

    /**
     * @test
     */
    public function inproper_message_because_wrong_format()
    {
        $response = $this->post('/api/slack/fetch', $this->getSlackRequest([
			'text' => 'blubry',
		]));

        $this->assertWrongFormat($response);

        $this->assertEquals(0, Kudos::count());
    }
    
}
