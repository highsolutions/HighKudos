<?php

namespace App\Services\Slack;

use App\Notifications\KudosNotification;

class SlashCommandResponder
{

	public static function error($reason)
	{
		switch($reason)
		{
			case 'validation-failed':
				return static::sendResponse('ephemeral', 'Wiadomość jest źle sformatowana. Wyślij kudosa jeszcze raz', [
					[
						'text' => 'np. dla @janusz za dużego deala #wyzwanie',
					]
				]);
		}
	}

	public static function confirmation()
	{
		return static::sendResponse('ephemeral', 'Właśnie dałeś karteczkę!');		
	}

	public static function empty()
	{
		return response('');
	}

	public static function success($kudos, $message)
	{
		$kudos->sender->notify(new KudosNotification($message));
	}

	protected static function sendResponse($response_type, $text, $attachments = [])
	{
		return response(compact('response_type', 'text', 'attachments'));
	}

}
