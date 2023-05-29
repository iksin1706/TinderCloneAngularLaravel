<?php

namespace App\Http\Controllers;

use App\Models\Blockage;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BanController extends Controller
{
    public function index(){
        if (!Auth::payload()->get('role') == 'admin') return response('Only admin has access',403);
        $reponse = Blockage::all();
        return response()->json($reponse);
    }

    public function ban(Request $request,$username){
        $user = Blockage::where('username',$username)->first();
        $ban = new Blockage();
        $ban->user=$user;
        $ban->admin=Auth::user();
        $ban->reason=$request->reason;
        $ban->until=$request->until;
        $ban->save();
        return response()->json(['message'=>'User blocked successfuly']);
    }
}
