<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Scout\Searchable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, JWTSubject
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use Notifiable;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role',
        'address',
        'email',
        'phone_number',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'role' => RoleEnum::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * {@inheritDoc}
     */
    public function getJWTCustomClaims()
    {
        return [
            'email' => $this->getAttributeValue('email'),
            'role' => $this->getAttributeValue('role'),
        ];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getRoleNameAttribute()
    {
        return $this->role->label();
    }

    public function toSearchableArray()
    {
        return [
            'name' => '',
            'address' => '',
            'email' => '',
            'phone_number' => '',
        ];
    }
}
