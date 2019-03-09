<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KudosValue extends Model
{
	protected $table = 'kudos_values';

    protected $fillable = [
        'kudos_id', 'text',
    ];

    public function kudos()
    {
    	return $this->belongsTo(Kudos::class, 'kudos_id');
    }

}
