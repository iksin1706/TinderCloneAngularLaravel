<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPhotoRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Photo;
use App\Repositories\UserRepository;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = User::all();

        if (Auth::user()) return response()->json($members, 200);
        return new UserCollection($members);
    }

    /**
     * Display the specified resource.
     */
    public function show($username)
    {
        $user = User::where('username',$username)->first();
        if($user)
        return new UserResource($user);
        else return response("Not found",404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return new UserResource($user->refresh());
    }
    public function addPhoto(AddPhotoRequest $request)
    {
        $request = $request->validated();
        if(!Auth::user()) return response("Not found",404);


        $uploadFolder = 'users';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');

        return response()->json(['path' => $image_uploaded_path],201);
    }

    public function setMainPhoto($id)
    {
        $currentMainPhoto = Photo::where('is_main', true)->first();
        if($currentMainPhoto) $currentMainPhoto->update("is_main", true);
        Photo::find($id)->update("is_main", true);

        return response("Succes",204);
    }

    public function deletePhoto($path)
    {
        // if(File::exists($image_path)) {
        //     File::delete($image_path);
        // }
        // $image = Storage::get($path);
        // return response($image, 200)->header('Content-Type', Storage::getMimeType($path));
    }
}
