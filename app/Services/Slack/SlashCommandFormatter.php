<?php

namespace App\Services\Slack;

use App\Models\Kudos;
use App\Models\User;

class SlashCommandFormatter
{

	protected static $kudos;

	public static function prepare($kudos, $pattern = 'normal')
	{		
		static::$kudos = $kudos;
    	$pattern = SlashCommandPatterns::getResponsePattern($pattern);
    	$replacements = [
    		'users' => static::getReceivers(),
    		'message' => static::getMessage(),
    		'values' => static::getValues(),
    		'sender' => static::getSender(),
    		'day' => static::getDay(),
    	];

    	return static::replace($pattern, $replacements);
	}

	protected static function replace($pattern, $replacements)
	{
		collect($replacements)
			->each(function ($value, $key) use (&$pattern) {
				$pattern = str_replace('@'. $key, $value, $pattern);
			});

		return trim(str_replace(['``', ' .'], ['', '.'], preg_replace('!\s+!', ' ', $pattern)));
	}

	protected static function getReceivers()
	{
		return collect(static::$kudos->receivers)
			->map(function ($receiver) {
				return $receiver->formatForSlack();
			})->implode(' ');
	}

	protected static function getMessage()
	{
		return static::$kudos->message;
	}

	protected static function getValues()
	{
		return collect(static::$kudos->values)
			->map(function ($value) {
				return '#'. $value->text;
			})->implode(' ');
	}

	protected static function getSender()
	{
		return static::$kudos->sender->formatForSlack();
	}

	protected static function getDay()
	{
		return static::$kudos->created_at->format('l');
	}

}
