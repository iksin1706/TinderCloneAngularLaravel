<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPhotoRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Dislike;
use App\Models\Like;
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
use Illuminate\Support\Str;

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
        $withoutLikes = $request->query('withoutLikes');

        $query = User::with('photos');
        if (Auth::user()) {
            $userId = Auth::user()->id;


            $dislikedUserIds = Dislike::where('source_user_id', $userId)->pluck('target_user_id');
            $likedUserIds = Like::where('source_user_id', $userId)->pluck('target_user_id');


            if ($withoutLikes == 'true') {
                $query = $query->whereNotIn('id', $likedUserIds);
                $query = $query->whereNotIn('id', $dislikedUserIds);
            }
        }



        if ($minAge) {
            $minBirthDate = date('Y-m-d', strtotime("-$minAge years"));
            $query->where('date_of_birth', '<=', $minBirthDate);
        }

        if ($maxAge) {
            $maxBirthDate = date('Y-m-d', strtotime("-$maxAge years"));
            $query->where('date_of_birth', '>=', $maxBirthDate);
        }

        if ($gender && $gender != 'any') {
            $query->where('gender', $gender);
        }

        if ($orderBy == 'created_at' || $orderBy == 'points' || $orderBy == 'last_active')
            $query->orderBy($orderBy, 'desc');




        $totalItems = $query->count();
        $totalPages = ceil($totalItems / $pageSize);

        $users = $query->skip(($pageNumber - 1) * $pageSize)
            ->take($pageSize)
            ->get();

        if (Auth::user())
            $users = $users->map(function ($user) use ($likedUserIds, $dislikedUserIds) {
                $userData = [
                    'userName' => $user->username,
                    'knownAs' => $user->known_as,
                    'age' => $user->age(),
                    'photoUrl' => optional($user->Photos->first(function ($photo) {
                        return $photo->is_main;
                    }))->url,
                    'city' => $user->city,
                    'country' => $user->country,
                    'id' => $user->id,
                    'created' => $user->created_at,
                    'photos' => $user->photos->sortByDesc('is_main')
                ];

                if (Auth::check()) {
                    if ($dislikedUserIds->contains($user->id)) {
                        $userData['likeStatus'] = 'disliked';
                    } elseif ($likedUserIds->contains($user->id)) {
                        $userData['likeStatus'] = 'liked';
                    } else {
                        $userData['likeStatus'] = 'none';
                    }
                }

                return $userData;
            });
        else {
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
                    'created' => $user->created_at,
                    'photos' => $user->photos,
                ];
            });
        }

        $response = response()->json(
            $users,
            200
        );

        $response->header('Content-Type', 'application/json');
        $response->header('Access-Control-Expose-Headers', 'Pagination');
        $response->header('Pagination', '{"currentPage":"' . $pageNumber . '","itemsPerPage":"' . $pageSize . '","totalItems":"' . $totalItems . '","totalPages":"' . $totalPages . '"}');
        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show($username)
    {
        $user = User::with('photos')->where('username', $username)->first();
        $user['photoUrl'] = $user->Photos->first(function ($photo) {
            return $photo->is_main;
        })?->url;
        $user['lookingFor'] = $user['looking_for'];
        $user['age'] = $user->age();
        $user['knownAs'] = $user['known_as'];
        $user['userName'] = $user['username'];
        if ($user)
            return $user;
        else return response("Not found", 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request)
    {
        $user = User::where('username', auth()->user()->username)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $request['looking_for'] = $request['lookingFor'];

        $user->fill($request->all());

        if (!$user->isDirty()) {
            return response()->json(['message' => 'No changes detected.'], 200);
        }

        if ($user->save()) {
            return response()->json(['message' => 'User updated successfully'], 200);
        }

        return response()->json(['error' => 'Failed to update user'], 500);
    }


    public function addPhoto(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return response()->json(['error' => 'Invalid file'], 400);
        }

        $file = $request->file('file');
        $path = $file->store('public/photos');

        $photo = new Photo();
        $photo->url = asset(Str::replaceFirst('public', 'storage', $path));
        $photo->public_id = '';
        $photo->user_id = $user->id;

        if ($user->photos->isEmpty()) {
            $photo->is_main = true;
        } else {
            $photo->is_main = false;
        }

        $photo->save();

        return response()->json($photo);
    }

    public function deletePhoto($id)
    {
        $photo = Photo::find($id);

        if (!$photo) {
            return response()->json(['error' => 'Photo not found'], 404);
        }

        $photoPath = public_path($photo->url);

        if (file_exists($photoPath)) {
            unlink($photoPath);
        }

        $photo->delete();

        return response()->json(['message' => 'Photo deleted successfully']);
    }

    public function setMainPhoto($photoId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $photo = Photo::where('id', $photoId)
            ->where('user_id', $user->id)
            ->first();

        if (!$photo) {
            return response()->json(['error' => 'Photo not found'], 404);
        }

        if ($photo->isMain) {
            return response()->json(['error' => 'This is already your main photo'], 400);
        }

        $currentMain = Photo::where('user_id', $user->id)
            ->where('is_main', true)
            ->first();

        if ($currentMain) {
            $currentMain->is_main = false;
            $currentMain->save();
        }

        $photo->is_main = true;
        $photo->save();

        return response()->json(['message' => 'Main photo set successfully']);
    }
}
