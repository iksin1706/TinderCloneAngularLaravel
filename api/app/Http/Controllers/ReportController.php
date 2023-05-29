<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(){
        if (!Auth::payload()->get('role') == 'admin') return response('Only admin has access',403);
        $reponse = Report::all();
        return response()->json($reponse);
    }

    public function report(Request $request,$username){
        $user = User::where('username',$username)->first();
        $report = new Report();
        $report->reported_id=$user->id;
        $report->reporting_id=Auth::user()->id;
        $report->reason=$request->reason;
        $report->save();
        return response()->json(['message'=>'User reported, thank you for making our service better place']);
    }
}
