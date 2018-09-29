<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'admin_user_profile';
	protected $fillable = [
        'adminuser_id','profile_image','about_me'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\User');
    }
}
