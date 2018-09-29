<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    protected $table = 'transaction_types';

    protected $fillable = [
    	'type',
    	'description_template'
    ];

    public $timestamps = false;
}
