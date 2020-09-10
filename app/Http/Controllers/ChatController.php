<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use Redis;
use Storage;

class ChatController extends Controller
{
    public function getMessages() //get messages

    {
        $messages = Message::all();

        return response()->json($messages, 200);
    }
    public function sendMessage(Request $request)
    {
        $data = $request->only(['name', 'body']);
        if ($request->has('image')) {
            $data['image'] = Storage::disk('uploads')->put('chat', $request->image);
        }
        $message = Message::create($data);

        $redis = Redis::connection();
        $redis->publish('message', json_encode($message));

        return response()->json($message, 200);
    }
}
