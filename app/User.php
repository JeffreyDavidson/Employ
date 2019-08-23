<?php

namespace App;

use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id',
    ];

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

    /**
     * Get the company of the user..
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Hash all users password when created.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Determine if a user is an administrator.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->role_id == Role::whereSlug('admin')->first()->id;
    }

    /**
     * Determine if a user is an administrator.
     *
     * @return boolean
     */
    public function isManager()
    {
        return $this->role_id == Role::whereSlug('manager')->first()->id;
    }

    public function scopeManagers($query)
    {
        return $query->where('role_id', Role::whereSlug('manager')->first()->id);
    }
}
