<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
class EntetePointage extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $table = 'entetes_pointages';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int',
	];

	protected $fillable = [
		'titre1',
		'titre1_ar',
		'titre2',
		'titre2_ar',
		'titre3',
		'titre3_ar',
		'logo'
	];
}
