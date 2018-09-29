<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	protected $fillable = [
		'userid',
		'title',
		'description',
		'tags',
		'type',
		'timestamp',
		'month',
		'year',
		'thumb',
		'image',
	];

	public $timestamps = false;
}
