<?php


namespace App\Http\Controllers\Api\V1;


use App\Group;
use App\Http\Controllers\Controller;
use App\Post;
use App\Project;
use App\ProjectStatuses;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    public function index()
    {
        return Project::all();
    }

    public function show($id)
    {
        return Project::with('statuses')->find($id);
    }

    public function store(Request $request)
    {
        $data['title'] = $request->get('title');
        $data['description'] = htmlspecialchars($request->get('description'));
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

    public function getTasksByStatuses(Request $request, $project_id) {

        $project = Project::find($project_id);
        var_dump($project);
        return response()->json($tasks);
    }

    public function getUserTasks(Request $request, $user_id) {
        $user = User::find($user_id);
        $tasks = $user->tasks()->with('status')->with('project')->get();
        return response()->json($tasks);
    }

}
