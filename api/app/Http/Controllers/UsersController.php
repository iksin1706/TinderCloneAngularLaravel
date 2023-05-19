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
    public function index(Request $request)
    {
        $pageNumber = $request->input('pageNumber', 1);
        $pageSize = $request->input('pageSize', 10);
        $minAge = $request->input('minAge');
        $maxAge = $request->input('maxAge');
        $gender = $request->input('gender');
        $orderBy = $request->input('orderBy', 'created_at');

        $query = User::with('photos');

        if ($minAge) {
            $query->where('age', '>=', $minAge);
        }

        if ($maxAge) {
            $query->where('age', '<=', $maxAge);
        }

        if ($gender) {
            $query->where('gender', $gender);
        }

        $query->orderBy($orderBy);

        $totalUsers = $query->count();

        $users = $query->skip(($pageNumber - 1) * $pageSize)
            ->take($pageSize)
            ->get();

        $users = collect($users)->map(function ($user) {
            return [
                'username' => $user->username,
                'knownAs' => $user->known_as,
                'age' => now()->diffInYears($user->date_of_birth),
                'photoUrl' => $user->Photos->first(function ($photo) {
                    return $photo->is_main;
                })->url,
                'city' => $user->city,
                'id' => $user->id
            ];
        });

        return response()->json(
            $users,
            200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($username)
    {
        $user = User::with('photos')->where('username', $username)->first();
        if ($user)
            return $user;
        else return response("Not found", 404);
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
        if (!Auth::user()) return response("Not found", 404);


        $uploadFolder = 'users';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');

        return response()->json(['path' => $image_uploaded_path], 201);
    }

    public function setMainPhoto($id)
    {
        $currentMainPhoto = Photo::where('is_main', true)->first();
        if ($currentMainPhoto) $currentMainPhoto->update("is_main", true);
        Photo::find($id)->update("is_main", true);

        return response("Succes", 204);
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
