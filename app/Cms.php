<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
    public function cmscontents()
    {
        return $this->hasMany('App\CmsContent','cms_id');
    }
}
