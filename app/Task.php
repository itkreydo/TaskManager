<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    public function status() {
        return $this->hasOne('App\ProjectStatuses', 'id', 'status_id');
    }

    public function project() {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }

    public function messages() {
        return $this->hasMany('App\TaskMessage', 'task_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'tasks_participants', 'task_id', 'user_id');
    }
}
