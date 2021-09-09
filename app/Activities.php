<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
	public function management()
	{
		return $this->belongsTo('App\Management');
	}
}
