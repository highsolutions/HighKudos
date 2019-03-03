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

	public static function confirmation()
	{
		return static::sendResponse('ephemeral', 'Właśnie dałeś karteczkę!');		
	}

	public static function empty()
	{
		return response('');
	}

	public static function success($message)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://hooks.slack.com/services/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');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
			'text' => $message,
		]));
		curl_setopt($ch, CURLOPT_POST, 1);

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		curl_close ($ch);
	}

	protected static function sendResponse($response_type, $text, $attachments = [])
	{
		return response(compact('response_type', 'text', 'attachments'));
	}

}
