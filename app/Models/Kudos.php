<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kudos extends Model
{
	protected $table = 'kudos';

    protected $fillable = [
        'sender_id', 'message',
    ];

    public function sender()
    {
    	return $this->belongsTo(User::class, 'sender_id');
    }

    public function receivers()
    {
    	return $this->belongsToMany(User::class, 'kudos_receivers', 'receiver_id', 'kudos_id');
    }

    public function values()
    {
    	return $this->hasMany(KudosValue::class);
    }

}
