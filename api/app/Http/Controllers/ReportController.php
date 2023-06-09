<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(){
        $role = Auth::payload()->get('role');
        if ($role !== 'admin' && $role !== 'moderator') {
            return response('Only admins and moderators has access', 403);
        }
        
        $response = Report::with(['reportedUser', 'reportingUser'])->get();
        
        $response->each(function ($report) {
            $report->reported_username = $report->reportedUser->username;
            $report->reporting_username = $report->reportingUser->username;
        });
        
        return response()->json($response);
    }

    public function report(StoreReportRequest $request,$username){
        $user = User::where('username',$username)->first();
        if(!$user)return response()->json(['User with that username doesnt exist'],404);
        if($user->id===Auth::user()->id)return response()->json(['You cant report yourself'],400);
        $report = new Report();
        $report->reported_id=$user->id;
        $report->reporting_id=Auth::user()->id;
        $report->reason=$request->reason;
        $report->save();
        return response()->json(['User reported, thank you for making our service better place'],201);
    }   
}
