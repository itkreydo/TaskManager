<?php

namespace App\Broadcasting;

use App\User;

class MessageChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\User  $user
     * @return array|bool
     */
    public function join(User $user, int $task_id)
    {
        //TODO Добавить проверку на доступность чата по городу
        return true || false;
    }
}
