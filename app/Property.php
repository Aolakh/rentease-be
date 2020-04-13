<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent; 

class Property extends Eloquent
{
    protected $connection = 'mongodb';
	protected $collection = 'property';

    protected $fillable = [
        'name', 'detail'
    ];
}
