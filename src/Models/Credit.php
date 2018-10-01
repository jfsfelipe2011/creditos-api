<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
	protected $fillable = [
        'value',
        'validity',
        'description',
        'balance',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}