<?php

namespace App\Models;

use Carbon\Carbon;
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
    	return $this->belongsToMany(User::class, 'kudos_receivers', 'kudos_id', 'receiver_id');
    }

    public function values()
    {
    	return $this->hasMany(KudosValue::class);
    }

    public function scopeThisWeek($query)
    {
        return $query->where('kudos.created_at', '>=', Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d 00:00:00'));
    }

}
