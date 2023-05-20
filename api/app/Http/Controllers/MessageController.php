<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Message;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $user = Auth::user();

        $messages = Message::with('users')
        ->select()
        ->where('recipient_username', $user->username)
        ->orWhere('sender_username', $user->username)
        ->groupBy('recipient_id')
        ->groupBy('sender_id')
        ->orderBy('message-sent', 'desc');

        return response()->json($messages);
    }

    public function store(Request $request, $username)
    {
        $validator = Validator::make($request->all(), [
            'recipient_username' => 'required',
            'content' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $username = Auth::user()->username;
    
        if (strtolower($username) === strtolower($request->recipient_username)) {
            return response()->json("You cannot send messages to yourself", 400);
        }
    
        $sender = User::where('username', $username)->first();
        $recipient = User::where('username', $request->recipient_username)->first();
    
        if (!$recipient) {
            return response()->json("Recipient not found", 404);
        }
    
        $message = new Message();
        $message->sender_id = $sender->id;
        $message->recipient_id = $recipient->id;
        $message->sender_username = $sender->username;
        $message->recipient_username = $recipient->username;
        $message->content = $request->content;
        $message->save();
    
        return response()->json($message, 200);
    }


    public function thread($username){

        $user = Auth::user();

        $messages = Message::with('users')
        ->where(function ($query) use ($user,$username) {
            $query->where('recipient_username', $user->username)
                ->where('sender_username', $username);
        })
        ->orWhere(function ($query) use ($user,$username) {
            $query->where('recipient_username', $username)
                ->where('sender_username', $user->username);
        })
        ->orderBy('message_sent', 'desc');

        return response()->json($messages);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
