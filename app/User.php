<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use TaylorNetwork\UsernameGenerator\FindSimilarUsernames;

class User extends Authenticatable
{
    use Notifiable, FindSimilarUsernames;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $fillable = [
        'name', 'email', 'password',
    ];*/

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($password){
        if(isset($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    } 

    public function classes()
    {
        return $this->belongsToMany('App\Classes','classes_students');
    }       

    public function school(){
    	return $this->belongsTo('App\School','school_id');
    }
	
	public function city()
	{
		return $this->belongsTo('App\City');
	}
	
	public function location()
	{
		return $this->belongsTo('App\Location');
	}

}
