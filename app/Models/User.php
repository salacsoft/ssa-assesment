<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'suffixname',
        'username',
        'email',
        'password',
        'photo',
        'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * encrypt the user's password
     * @param String $pass
     */
    public function setPasswordAttribute($pass)
    {

        $this->attributes['password'] = Hash::make($pass);
        
    }

    /**
     * setAttribute to get avatar
     * $this->avatar
     */
    public function getAvatarAttribute()
    {
        return $this->photo;
    }


        /**
     * setAttribute to get fullname
     * $this->fullname
     */
    public function getFullnameAttribute()
    {
        return ucfirst($this->firstname) . " " . strtoupper(substr($this->middlename,0,1)) . ". " . ucfirst($this->lastname);
    }

}
