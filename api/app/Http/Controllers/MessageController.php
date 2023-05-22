<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $user = Auth::user();

        $messages = Message::where('recipient_id', $user->id)
        ->orWhere('sender_id', $user->id)
        ->groupBy('recipient_id')
        ->groupBy('sender_id')
        ->orderBy('message_sent', 'desc')
        ->get();

        


        $messages = collect($messages)->map(function ($message) {
            $photoUrl = User::where('id',$message->recipient_id)->first()->Photos->first(function ($photo) {
                return $photo->is_main;
            })?->url;
            return [
                'content' => $message->content,
                'photoUrl' => $photoUrl,
                'username' => $message->recipient_username,
                'dateRead' => $message->date_read
            ];
        });

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $username = Auth::user()->username;
    
        if (strtolower($username) === strtolower($request->recipient_username)) {
            return response()->json("You cannot send messages to yourself", 400);
        }
    
        $sender = User::where('username', $username)->first();
        $recipient = User::where('username', $request->recipient_username)->first();
        // if (!$recipient) {
        //     return response()->json("Recipient not found", 404);
        // }
    
        $responseMessage = new Message();
        $responseMessage->sender_id = $sender->id;
        $responseMessage->recipient_id = $recipient->id;
        $responseMessage->sender_username = $sender->username;
        $responseMessage->recipient_username = $recipient->username;
        $responseMessage->content = $request->content;
        $responseMessage->save();
    
        return response()->json($responseMessage, 200);
    }


    public function thread($username){

        $user = Auth::user();

        $messages = Message::where(function ($query) use ($user,$username) {
            $query->where('recipient_username', $user->username)
                ->where('sender_username', $username);
        })
        ->orWhere(function ($query) use ($user,$username) {
            $query->where('recipient_username', $username)
                ->where('sender_username', $user->username);
        })
        ->orderBy('message_sent', 'desc')->get();

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
