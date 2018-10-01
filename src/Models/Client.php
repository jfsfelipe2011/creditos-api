<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	protected $fillable = [
		'name',
		'document',
		'email',
		'fone',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function extract()
    {
        return $this->hasMany(Extract::class)->orderBy('date', 'asc');
    }

    public function credit()
    {
        return $this->hasMany(Credit::class)->orderBy('validity', 'asc');
    }
}