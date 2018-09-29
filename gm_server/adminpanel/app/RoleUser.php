<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
	protected $table = 'role_admin_user';
    //
    protected $fillable = ['adminuser_id','role_id'];

    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo('App\Role');
    }
}
