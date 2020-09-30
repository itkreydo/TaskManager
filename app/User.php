<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'patronymic', 'email', 'password', 'post_id', 'date_of_birth'
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

    public function post() {
        return $this->hasOne('App\Post', 'id', 'post_id');
    }

    public function settings() {
        return $this->hasOne('App\UsersSettings', 'user_id', 'id');
    }

    public function avatar() {
        return $this->hasOne('App\File', 'id', 'avatar_id');
    }

    public function getAvatarAttribute() {
        return $this->avatars->latest()->first();
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Task', 'tasks_participants', 'user_id', 'task_id');
    }
}
