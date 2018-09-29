<?php

namespace App;
use Zizaco\Entrust\Traits\EntrustUserTrait;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
	use SoftDeletes;
	use EntrustUserTrait {
		EntrustUserTrait::restore insteadof SoftDeletes;
	}

    protected $table = 'adminusers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','is_admin','deleted',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	 /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
	
	public function isAdmin()
	{
	    return $this->is_admin; // this looks for an admin column in users table
	}
	
	public function user_profile()
	{
		return $this->hasOne('App\UserProfile');
	}
}
