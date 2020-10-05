<?php

namespace App\Services\Slack;

class SlashCommandValidator
{

	public static function check($message)
	{
		$partials = static::getPartials($message);

		if($partials == false || sizeof($partials) < 2)
			return false;

		// if(! static::checkUsers($partials[1]))
		// 	return false;

		if(isset($partials[1]) && ! static::checkMessage($partials[1]))
			return false;

		if(isset($partials[2]) && ! static::checkValues($partials[2]))
			return false;

		return true;
	}

	protected static function getPartials($message)
	{
		preg_match_all(SlashCommandPatterns::getOverallPattern(), $message, $matches, PREG_SET_ORDER, 0);

		if(sizeof($matches) == 0)
			return false;

		return $matches[0];
	}

	protected static function checkUsers($users)
	{
		preg_match_all(SlashCommandPatterns::getUsersPattern(), $users, $matches, PREG_SET_ORDER, 0);

		foreach($matches as $match) 
		{
			if(sizeof($match) > 1)
				return true;
		}

		if($users == '@all')
			return true;

		return false;
	}

	protected static function checkMessage($message)
	{
		return $message != '';
	}

	protected static function checkValues($values)
	{
		preg_match_all(SlashCommandPatterns::getValuesPattern(), $values, $matches, PREG_SET_ORDER, 0);

		foreach($matches as $match) 
		{
			if(sizeof($match) < 2 || strpos(trim($match[0]), '#') !== 0)
				return false;
		}
		
		return true;
	}

}
