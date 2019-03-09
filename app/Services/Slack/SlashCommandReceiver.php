<?php

namespace App\Services\Slack;

class SlashCommandReceiver
{

	public static function get($parameters)
	{
		if(! SlashCommandValidator::check(array_get($parameters, 'text')))
			return SlashCommandResponder::error('validation-failed');

		$partials = SlashCommandAnalyzer::handle($parameters);

		$kudos = SlashCommandPersister::store($partials, $parameters);

		$response = SlashCommandFormatter::prepare($kudos);

		SlashCommandResponder::success($kudos, $response);

		return SlashCommandResponder::empty();
	}


}
