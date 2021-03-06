<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Hash;

/**
 * Class User.
 *
 * @package namespace App\Entities;
 */
class User extends Authenticatable implements Transformable, JWTSubject
{
    use SoftDeletes, TransformableTrait, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','department','phone_number', 'email', 'password','role_id'
    ];

    /*
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
    * ACCESSORS
    */
    public function getRoleAttribute(){
        $roles = [
            1 => 'admin'
        ];
        if(array_key_exists($this->role_id,$roles)){
            return $roles[$this->role_id];
        }
        return null;
    }

    /*
    * MUTATORS
    */
    public function setPasswordAttribute($value)
    {
        if (!$value) {
            $value = "password";
        }
        if (strlen($value) >= 60 && preg_match('/^\$2y\$/', $value)) {
            return $this->attributes['password'] = $value;
        }

        $this->attributes['password'] = \Hash::make($value);
    }

    /*
    * JWT INTERFACE
    */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
