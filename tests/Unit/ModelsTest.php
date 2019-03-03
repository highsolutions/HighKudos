<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Kudos;
use App\Models\KudosValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelsTest extends TestCase
{
	use RefreshDatabase;

    /**
     * @test
     */
    public function create_simple_kudos()
    {
    	$sender = factory(User::class)->create([
    		'name' => 'John Doe',
    	]);

    	$receiver = factory(User::class)->create([
    		'name' => 'Jack Sparrow',
    	]);

        $kudos = factory(Kudos::class)
        	->create([
        		'sender_id' => $sender->id,
        		'message' => 'test message',
        	]);

        $kudos->receivers()->save($receiver);

        $this->assertEquals('test message', $kudos->message);
        $this->assertEquals('John Doe', $kudos->sender->name);

        tap($kudos->receivers, function ($receivers) {
        	$this->assertEquals(1, $receivers->count());
        	$this->assertEquals('Jack Sparrow', $receivers->first()->name);
        });
    }

    /**
     * @test
     */
    public function create_kudos_with_many_receivers()
    {
    	$sender = factory(User::class)->create([
    		'name' => 'John Doe',
    	]);

    	$receiver1 = factory(User::class)->create([
    		'name' => 'Jack Sparrow',
    	]);

    	$receiver2 = factory(User::class)->create([
    		'name' => 'Will Turner',
    	]);

        $kudos = factory(Kudos::class)
        	->create([
        		'sender_id' => $sender->id,
        		'message' => 'test message',
        	]);

        $kudos->receivers()->save($receiver1);
        $kudos->receivers()->save($receiver2);

        $this->assertEquals('test message', $kudos->message);
        $this->assertEquals('John Doe', $kudos->sender->name);

        tap($kudos->receivers, function ($receivers) {
        	$this->assertEquals(2, $receivers->count());
        	$this->assertEquals('Jack Sparrow', $receivers->first()->name);
        	$this->assertEquals('Will Turner', $receivers->last()->name);
        });
    }

    /**
     * @test
     */
    public function create_kudos_with_one_value()
    {
        $kudos = factory(Kudos::class)
        	->create();

        factory(KudosValue::class)
        	->create([
        		'kudos_id' => $kudos->id,
        		'text' => 'engagement',
        	]);

        tap($kudos->values, function ($values) {
        	$this->assertEquals(1, $values->count());
        	$this->assertEquals('engagement', $values->first()->text);
        });
    }

    /**
     * @test
     */
    public function create_kudos_with_two_values()
    {
        $kudos = factory(Kudos::class)
        	->create();

        factory(KudosValue::class)
        	->create([
        		'kudos_id' => $kudos->id,
        		'text' => 'engagement',
        	]);

        factory(KudosValue::class)
        	->create([
        		'kudos_id' => $kudos->id,
        		'text' => 'trust',
        	]);

        tap($kudos->values, function ($values) {
        	$this->assertEquals(2, $values->count());
        	$this->assertEquals('engagement', $values->first()->text);
        	$this->assertEquals('trust', $values->last()->text);
        });
    }
    
}
