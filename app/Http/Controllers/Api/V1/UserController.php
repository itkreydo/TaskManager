<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\User;
use App\UsersSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return User::with('post')->with('avatar')->get();
    }

    public function show($id)
    {
        return User::with('avatar')->with('post')->find($id);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'patronymic' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'post_id' => 'required',
            'date_of_birth' => 'required',
            'phone' => 'string'
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['avatar_id'] = 1;
        $user = User::create($validatedData);

        $us = new UsersSettings();
        $us->user_id = $user->id;
        $us->save();

        return $user;
    }

    public function update(Request $request, $id)
    {
        $article = User::findOrFail($id);
        $article->update($request::all());

        return $article;
    }

    public function delete(Request $request, $id)
    {
        $article = User::findOrFail($id);
        $article->delete();

        return 204;
    }

}

