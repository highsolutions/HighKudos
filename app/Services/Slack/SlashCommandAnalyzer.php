<?php

namespace App\Services\Slack;

use App\Models\Kudos;
use App\Models\User;
use Illuminate\Support\Arr;

class SlashCommandAnalyzer
{

	public static function handle($parameters)
	{
		$partials = static::getPartials($parameters['text']);

		return [
			'full_message' => $parameters['text'],
			'receivers' => static::getReceivers(Arr::get($partials, 1, ''), $parameters['user_id']),
			'message' => static::getMessage($partials),
			'values' => static::getValues(Arr::get($partials, 3, '')),
		];
	}

	protected static function getPartials($message)
	{
		preg_match_all(SlashCommandPatterns::getOverallPattern(), $message, $matches, PREG_SET_ORDER, 0);

		return $matches[0];
	}

	protected static function getReceivers($users, $sender)
	{
		if($users == '@all')
			return static::getAllReceivers($sender);
		
		return static::getFewReceivers($users);			
	}

	protected static function getAllReceivers($sender)
	{
		return User::query()
			->where('slack_id', '<>', $sender)
			->get()
			->map(function ($user) {
				return $user->formatForSlack();
			});
	}

	protected static function getFewReceivers($users)
	{
		preg_match_all(SlashCommandPatterns::getUsersPattern(), $users, $matches, PREG_SET_ORDER, 0);

		return collect($matches)
			->map(function ($user) {
				return $user[1];
			})->all();
	}

	protected static function getMessage($partials)
	{
 		return trim(Arr::get($partials, 2, '') . ' '. static::getFinishingEmoji(Arr::get($partials, 3, '')));
	}

	protected static function getValues($values)
	{
		preg_match_all(SlashCommandPatterns::getValuesPattern(), $values, $matches, PREG_SET_ORDER, 0);

		return collect($matches)
			->map(function ($value) {
				return trim(str_replace('#', '', $value[1]));
			})->all();
	}

	protected static function getFinishingEmoji($finish)
	{
		preg_match_all(SlashCommandPatterns::getEmojiPattern(), $finish, $matches, PREG_SET_ORDER, 0);

		return collect($matches)
			->map(function ($emoji) {
				return $emoji[1];
			})->implode(' ');
	}

}
