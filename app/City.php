<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    //use SoftDeletes;
	public function getarea()
    {
        return $this->hasMany('App\Location', 'city_id');
    } 
   
}
