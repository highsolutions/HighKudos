<?php

namespace App\Services\Slack;

use Illuminate\Support\Arr;

class SlashCommandReceiver
{

	public static function get($parameters)
	{
		if(! SlashCommandValidator::check(Arr::get($parameters, 'text')))
			return SlashCommandResponder::error('validation-failed');

		$partials = SlashCommandAnalyzer::handle($parameters);

		$kudos = SlashCommandPersister::store($partials, $parameters);

		$response = SlashCommandFormatter::prepare($kudos);

		SlashCommandResponder::success($kudos, $response);

		return SlashCommandResponder::empty();
	}


}
