<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Email extends Model
{
    //
    use SoftDeletes;
	
    protected $fillable = ['emails','subject','body','','datetime','sent'];
    protected $dates = ['deleted_at'];

	public function template(){
		return $this->belongsTo(Template::class);
	}
	
}
