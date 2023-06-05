<?php

namespace App\Http\Controllers;

use App\Models\Blockade;
use App\Models\Blockage;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BanController extends Controller
{
    public function index(){
        if (!Auth::payload()->get('role') == 'admin') return response('Only admin has access',403);
        $reponse = Blockade::all();
        return response()->json($reponse);
    }
    

    public function ban(Request $request,$username){
        if (!Auth::payload()->get('role') == 'admin') return response('Only admin has access',403);
        $user = User::where('username',$username)->first();
        $ban = new Blockade();
        $ban->user_id=$user->id;
        $ban->admin_id=Auth::user()->id;
        $ban->reason=$request->reason;
        $ban->until=$request->until;
        $ban->save();
        return response()->json(['User blocked successfuly'],200);
    }
}
