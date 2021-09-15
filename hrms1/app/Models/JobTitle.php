<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    use HasFactory;

    protected $fillable=['job_title_name','department_id','rate_per_hour'];

    public function department(){
        return $this->belongsTo('App\Models\Department');
    }

    public function onlineapplicant() {
        return $this->hasMany('App\Models\OnlineApplicant');
    }
}
