<?php

namespace App\Services\Slack;

use App\Models\Kudos;
use App\Models\KudosValue;
use App\Models\User;
use Carbon\Carbon;

class SlashCommandPersister
{

	public static function store($partials, $parameters)
	{
		$sender = static::getSender($parameters);
		$kudos = static::createKudos($sender, $partials['message']);
		//static::addReceivers($kudos, $partials['receivers']);
		static::addValues($kudos, $partials['values']);

		return $kudos;
	}

	protected static function getSender($parameters)
	{
		return User::findOrCreateFromSlack('<@'. $parameters['user_id'] .'|'. $parameters['user_name'] .'>');
	}

	protected static function createKudos($sender, $message)
	{
		return Kudos::create([
			'sender_id' => $sender->id,
			'message' => $message,
		]);
	}

	protected static function addReceivers($kudos, $receivers)
	{
		collect($receivers)
			->each(function ($receiver) use ($kudos) {
				$kudos->receivers()->save(User::findOrCreateFromSlack($receiver), [
					'created_at' => Carbon::now(),
					'updated_at' => Carbon::now(),
				]);
			});
	}

	protected static function addValues($kudos, $values)
	{
		if(empty($values))
			return;

		collect($values)
			->each(function ($value) use ($kudos) {
				KudosValue::create([
					'kudos_id' => $kudos->id,
					'text' => $value,
				]);
			});
	}

}
