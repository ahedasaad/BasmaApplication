<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function sendMessage(Request $request,$receiver_id)
    {
        $message = Message::create([
            'sender_id' => Auth::id(),  // استخدام معرف المستخدم الحالي
            'receiver_id' => $receiver_id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message, $request->receiver_id))->toOthers();

        return response()->json(['status' => 'Message sent']);
    }

    public function sendToUsers(Request $request)
    {
        $senderId = Auth::id();
        $messageText = $request->message;
        $receiverIds = $request->receiver_ids;

        // التأكد من أن receiver_ids هو مصفوفة
        if (is_string($receiverIds)) {
            $receiverIds = json_decode($receiverIds, true);
        }

        if (!is_array($receiverIds)) {
            return response()->json(['status' => 'Invalid receiver_ids format'], 400);
        }

        foreach ($receiverIds as $receiverId) {
            // حفظ الرسالة لكل مستلم
            $message = Message::create([
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'message' => $messageText,
            ]);

            // بث الرسالة إلى القناة الخاصة بالمستلم
            broadcast(new MessageSent($message, $receiverId))->toOthers();
        }

        return response()->json(['status' => 'Messages sent']);
    }


    public function getMessages($receiver_id)
    {
        $sender_id = Auth::id();  // الحصول على معرف المستخدم الحالي

        $messages = Message::where(function($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $sender_id)->where('receiver_id', $receiver_id);
        })->orWhere(function($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $receiver_id)->where('receiver_id', $sender_id);
        })->get();

        return response()->json($messages);
    }




}
