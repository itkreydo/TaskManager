<?php


namespace App\Http\Controllers\Api\V1;


use App\File;
use App\Group;
use App\Http\Controllers\Controller;
use App\Post;
use App\Services\KeyService\KeyStore;
use App\Task;
use App\TaskMessage;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{

    public function __construct()
    {

    }

    public function getMessages(Request $request, $project_id, $task_id) {
        $task = Task::find($task_id);
        $messages = $task->messages()->with('user.avatar')->get();
        return response()->json($messages);
    }

    public function newMessage(Request $request, $project_id, $task_id) {
        $text = $request->get('text');

        $task = new TaskMessage();
        $task->task_id = $task_id;
        $task->user_id = Auth::user()->id;
        $task->text = $text;
        $task->save();

        broadcast(new \App\Events\Chat\NewMessage($task))->toOthers();
        return response()->json(TaskMessage::with('user.avatar')->find($task->id));
    }

}
