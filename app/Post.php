<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $hidden = [
        'created_at', 'updated_at', 'group_id'
    ];
}
