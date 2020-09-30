<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title', 'description', 'status', 'owner_id'
    ];
    //
    public function users()
    {
        return $this->belongsToMany('App\User', 'projects_participants', 'project_id', 'user_id');
    }

    public function statuses() {
        return $this->hasMany('App\ProjectStatuses', 'project_id', 'id');
    }

    public function tasks() {
        return $this->hasMany('App\Task', 'project_id', 'id');
    }

    public function owner() {
        return $this->hasOne('App\User', 'id', 'owner_id');
    }
}
