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

    public function credit()
    {
        $data = new \DateTime;

        return $this->hasMany(Credit::class)
                                    ->where([
                                            ['validity', '>=', $data->format('Y-m-d')],
                                            ['balance', '>', 0]
                                        ])
                                    ->orderBy('validity', 'asc');
    }

    public function extract()
    {
        return $this->hasMany(Extract::class)->orderBy('date', 'asc');
    }

    public function getSaldo()
    {
        $data = new \DateTime;

        return $this->hasMany(Credit::class)
                                    ->where('validity', '>=', $data->format('Y-m-d'))
                                    ->sum('balance');
    }

    public function getCreditosAEstornar()
    {
        $data = new \DateTime;

        return $this->hasMany(Credit::class)
                                    ->where('validity', '>=', $data->format('Y-m-d'))
                                    ->orderBy('validity', 'desc');
    }

    public function getQuantidadeDeCreditos()
    {
        $data = new \DateTime;

        return $this->hasMany(Credit::class)
                                    ->where([
                                            ['validity', '>=', $data->format('Y-m-d')],
                                        ])
                                    ->count();
    }

    public function getQuantidadeDeCreditosAtivos()
    {
        $data = new \DateTime;

        return $this->hasMany(Credit::class)
                                    ->where([
                                            ['validity', '>=', $data->format('Y-m-d')],
                                            ['balance', '>', 0]
                                        ])
                                    ->count();
    }
}