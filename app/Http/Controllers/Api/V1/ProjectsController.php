<?php


namespace App\Http\Controllers\Api\V1;


use App\Group;
use App\Http\Controllers\Controller;
use App\Post;
use App\Project;
use App\ProjectStatuses;
use App\Task;
use App\TaskStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    public function index()
    {
        return Project::with('owner.avatar')
            ->with('users.avatar')
            ->withCount('users')
            ->withCount('tasks')->get();
    }

    public function show($id)
    {
        return Project::with('statuses')->find($id);
    }

    public function store(Request $request)
    {
        $data['title'] = $request->get('title');
        $data['description'] = $request->get('description');
        $data['status'] = 1;
        $data['owner_id'] = Auth::user()->id;
        $workers = $request->get('workers');

        $project = Project::create($data);
        $project->users()->attach($workers);
        return $project;
    }

    public function update(Request $request, $id)
    {
        $article = Project::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    public function delete(Request $request, $id)
    {
        $article = Project::findOrFail($id);
        $article->delete();

        return 204;
    }

    public function getTasksByStatuses(Request $request, $project_id)
    {

        $project = Project::find($project_id);
        return response()->json($project->tasks()->with('status')->with('users.avatar')->get());
    }

    public function createTask(Request $request, $id)
    {
        $project = Project::find($id);
        $task = new Task();
        $task->title = $request->get('title');
        $task->description = $request->get('description');
        $task->owner_id = Auth::user()->id;
        $task->status_id = $project->statuses->first()->id;
        $task->project_id = $id;
        $task->save();

        $taskStatus = new TaskStatusHistory();
        $taskStatus->user_id = Auth::user()->id;
        $taskStatus->task_id = $task->id;
        $taskStatus->project_status_id = $task->status_id;
        $taskStatus->save();
        $task->users()->attach($request->get('workers'));

        return $task;
    }

    public function getTasksById(Request $request, $project_id, $task_id)
    {
        $task = Project::find($project_id)->tasks()->with('users.avatar')->find($task_id);
        return response()->json($task);
    }

    public function updateTaskStatus(Request $request, $project_id, $task_id)
    {
        $task = Project::find($project_id)->tasks()->find($task_id);
        $task->status_id = $request->get('status_id');
        $task->save();

        $taskStatus = TaskStatusHistory::where('task_id', $task->id)
            ->latest()
            ->first();
        if ($taskStatus) {
            $taskStatus->end_date = now()->format('Y-m-d H:i:s');
            $taskStatus->save();
        }

        $taskStatus = new TaskStatusHistory();
        $taskStatus->user_id = Auth::user()->id;
        $taskStatus->task_id = $task->id;
        $taskStatus->project_status_id = $task->status_id;
        $taskStatus->save();
        return response()->json($task);
    }


}
