<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function role(){
       return $this->belongsTo(Role::class);
    }

    public function vechicle(){
       return $this->belongsTo(Vechicle::class,'vehicle_id','id');
    }

    public function useraddress(){
        return $this->hasMany(Useraddress::class);
    }

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }
}
