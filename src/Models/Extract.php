<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extract extends Model
{
	protected $fillable = [
		'type',
		'description',
		'date',
		'credits',
		'operation',
		'balance',
		'client_id'
	];

	public function client()
	{
		return $this->belongsTo(Client::class);
	}
}