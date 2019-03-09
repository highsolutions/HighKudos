<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Services\Slack\SlashCommandPatterns;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'username', 'slack_id', 'avatar_url',
    ];

    public function kudosGiven()
    {
    	return $this->hasMany(Kudos::class, 'sender_id', 'id');
    }

    public function kudosReceived()
    {
    	return $this->belongsToMany(Kudos::class, 'kudos_receivers', 'kudos_id', 'receiver_id');
    }

    public static function findOrCreateFromSlack($fullSlackIdentifier)
    {
    	$pattern = SlashCommandPatterns::getUserPattern();
		preg_match_all($pattern, $fullSlackIdentifier, $matches, PREG_SET_ORDER, 0);

		$user = User::firstOrNew([
			'slack_id' => $matches[0][1],
		]);

		if($user->exists == false) {
			$user->username = $matches[0][2];
			$user->name = $matches[0][2];
			$user->avatar_url = '';
			$user->save();
		} else if($user->username != $matches[0][2]) {
			$user->update([
				'username' => $matches[0][2],
			]);
		}

		return $user;
    }

    public function formatForSlack()
    {
    	return '<@'. $this->slack_id .'|'. $this->username .'>';
    }

    public function routeNotificationForSlack($notification)
    {
        return config('kudos.incoming_url');
    }

}
