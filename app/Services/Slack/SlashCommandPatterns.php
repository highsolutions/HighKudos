<?php

namespace App\Services\Slack;

class SlashCommandPatterns
{

	public static function getOverallPattern()
	{
		return '/^(.+)\s*([^<]#.*+)*$/mUu';
	}

	public static function getUsersPattern()
	{
		return '/(<@[a-z\d-]+\|[a-z\d-\.]+>)/miu';
	}

	public static function getUserPattern()
	{
		return '/<@([a-z\d-]+)\|([a-z\d\-\.]+)>/mui';
	}

	public static function getValuesPattern()
	{
		return '/([^<]#[\w\d-]+)/miu';
	}

	public static function getEmojiPattern()
	{
		return '/([\:|\;][\w\)\>\d-]+\:?)/miu';
	}

	public static function getResponsePattern($type = 'normal')
	{
		switch($type)
		{
			case 'summary':
				return 'Wykonałeś/-aś improv _@message_ `@values`.';
			default: 
				return '@sender wykonał/-a improva _@message_ `@values`.';
		}
	}

}
