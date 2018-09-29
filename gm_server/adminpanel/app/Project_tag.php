<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project_tag extends Model
{
	//use SoftDeletes;

	//Table name defined for this model
    protected $table = 'project_tags';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name',
    ];

    public $timestamps = false;
	
	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
   // protected $dates = ['deleted_at'];
	
	public static $rules = [
    	'name' => 'sometimes|required|unique:project_tags,name',
	];
	
	public function project(){
		return $this->belongsToMany('App\Project','tags');
	}
}
