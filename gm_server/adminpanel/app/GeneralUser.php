<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralUser extends Model
{
    //Table name defined for this model
    protected $table = 'users';

    protected $fillable = [
    	'username',
    	'password',
    	'useremail',
    	'accountType',
        'gender',
    	'birthDate',
    	'registerDate',
        'signedInDate',
        'user_code',
        'provider'
    ];

    public $timestamps = false;
}
