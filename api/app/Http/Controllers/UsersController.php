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
        $pageNumber = $request->query('pageNumber', 1);
        $pageSize = $request->query('pageSize', 10);
        $minAge = $request->query('minAge');
        $maxAge = $request->query('maxAge');
        $gender = $request->query('gender');
        $orderBy = $request->query('orderBy', 'created_at');

        $query = User::with('photos');

        if ($minAge) {
            $minBirthDate = date('Y-m-d', strtotime("-$minAge years"));
            $query->where('date_of_birth', '<=', $minBirthDate);
        }
        
        if ($maxAge) {
            $maxBirthDate = date('Y-m-d', strtotime("-$maxAge years"));
            $query->where('date_of_birth', '>=', $maxBirthDate);
        }

        if ($gender) {
            $query->where('gender', $gender);
        }

        $query->orderBy($orderBy);

        $totalItems = $query->count();
        $totalPages = ceil($totalItems/$pageSize);

        $users = $query->skip(($pageNumber - 1) * $pageSize)
            ->take($pageSize)
            ->get();

        $users = collect($users)->map(function ($user) {
            return [
                'userName' => $user->username,
                'knownAs' => $user->known_as,
                'age' => $user->age(),
                'photoUrl' => $user->Photos->first(function ($photo) {
                    return $photo->is_main;
                })?->url,
                'city' => $user->city,
                'country' => $user->country,
                'id' => $user->id,
                'created' => $user->created_at
            ];
        });

        $response = response()->json(
            $users,
            200
        );

        $response->header('Content-Type','application/json');
        $response->header('Access-Control-Expose-Headers','Pagination');
        $response->header('Pagination', '{"currentPage":"'.$pageNumber.'","itemsPerPage":"'.$pageSize.'","totalItems":"'.$totalItems.'","totalPages":"'.$totalPages.'"}');
        return $response;
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
