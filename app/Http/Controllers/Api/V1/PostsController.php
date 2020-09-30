<?php


namespace App\Http\Controllers\Api\V1;


use App\Group;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Support\Facades\Request;

class PostsController extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function show($id)
    {
        return Post::find($id);
    }

    public function store(Request $request)
    {
        return Post::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $article = Post::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    public function delete(Request $request, $id)
    {
        $article = Post::findOrFail($id);
        $article->delete();

        return 204;
    }

}
