<?php

namespace App\Http\Controllers;

use App\Http\Requests\BanRequest;
use App\Models\Blockade;
use App\Models\Blockage;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BanController extends Controller
{
    public function index(){
        if (Auth::payload()->get('role') !== 'admin') return response('Only admin has access',403);
        $reponse = Blockade::all();
        return response()->json($reponse);
    }
    
    public function ban(BanRequest $request,$username){
        $request->validated();
        $user = User::where('username', $username)->first();
        if(!$user)return response()->json(['User with that username doesnt exist'],404);
        if ($user->role->name === 'admin' || $user->role->name === 'moderator') {
            return response()->json(['Forbidden'], 403);
        }
        $ban = new Blockade();
        $ban->user_id=$user->id;
        $ban->admin_id=Auth::user()->id;
        $ban->reason=$request->reason;
        $ban->until=$request->until;
        $ban->save();
        return response()->json(['User blocked successfuly'],204);
    }

    public function unban($username){
        $role = Auth::payload()->get('role');
        if ($role !== 'admin' && $role !== 'moderator') return response('Only admin has access',403);
        $user = User::where('username', $username)->firstOrFail();
        $currentDate = Carbon::now();
        Blockade::where('user_id', $user->id)
            ->where('until', '>=', $currentDate)
            ->delete();
    
        return response()->json(['User unblocked successfuly'],204);
    }
}
