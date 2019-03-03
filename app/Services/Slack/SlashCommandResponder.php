<?php

namespace App\Services\Slack;

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

	public static function success($message)
	{
		return static::sendResponse('ephemeral', $message);		
	}

	protected static function sendResponse($response_type, $message, $attachments = [])
	{
		return response(compact('response_type', 'message', 'attachments'));
	}

}
