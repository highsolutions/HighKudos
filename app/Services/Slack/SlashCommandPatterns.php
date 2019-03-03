<?php

namespace App\Services\Slack;

class SlashCommandPatterns
{

	public static function getOverallPattern()
	{
		return '/^dla (.+) za (.+)\s*(#.*+)*$/mUu';
	}

	public static function getUsersPattern()
	{
		return '/(<@[a-z\d-]+\|[a-z\d-]+>)/miu';
	}

	public static function getUserPattern()
	{
		return '/<@([a-z\d-]+)\|([a-z\d-]+)>/mui';
	}

	public static function getValuesPattern()
	{
		return '/(#[\w\d-]+)/miu';
	}

	public static function getEmojiPattern()
	{
		return '/([\:|\;][\w\)\>\d-]+\:?)/miu';
	}

	public static function getResponsePattern()
	{
		return 'Kudos dla @users _za @message_ `@values` od @sender';
	}

}
