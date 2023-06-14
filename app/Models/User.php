<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'DNI',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'DNI',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(){

        return $this->belongsToMany(Role::class, 'user-roles');

    }

    public function subjects(){

        return $this->belongsToMany(Subject::class, 'taughts');

    }

    public function unities(){

        return $this->belongsToMany(Unity::class, 'taughts');

    }

    public function incidences()
    {
        return $this->hasMany(Incidence::class);
    }

    public function hasRole($roleName)
    {
    foreach ($this->roles as $role) {
        if ($role->name === $roleName) {
            return true;
        }
    }
    return false;
    }
}
