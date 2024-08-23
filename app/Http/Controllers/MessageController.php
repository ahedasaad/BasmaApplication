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

//
//    public function getMessages($receiver_id)
//    {
//        $sender_id = Auth::id();  // الحصول على معرف المستخدم الحالي
//
//        $messages = Message::where(function($query) use ($sender_id, $receiver_id) {
//            $query->where('sender_id', $sender_id)->where('receiver_id', $receiver_id);
//        })->orWhere(function($query) use ($sender_id, $receiver_id) {
//            $query->where('sender_id', $receiver_id)->where('receiver_id', $sender_id);
//        })->get();
//
//        return response()->json($messages);
//    }



    public function getMessages($receiver_id)
    {
        $sender_id = Auth::id();  // الحصول على معرف المستخدم الحالي

        // جلب الرسائل
        $messages = Message::where(function($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $sender_id)->where('receiver_id', $receiver_id);
        })->orWhere(function($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $receiver_id)->where('receiver_id', $sender_id);
        })->get();

        // تعديل الرسائل حسب نوع الحساب
        foreach ($messages as $message) {
            $sender = User::find($message->sender_id);
            $receiver = User::find($message->receiver_id);

            // إذا كان user_name غير موجود أو null، استبدله بـ 'admin'
            $message->sender_name = $sender ? ($sender->name ?? 'admin') : 'admin';
            $message->receiver_name = $receiver ? ($receiver->name ?? 'admin') : 'admin';

            // تعديل الرسالة حسب نوع الحساب
            switch ($sender->account_type) {
                case 'admin':
                    $message->message_admin = $message->message;
                    unset($message->message);  // إزالة الحقل القديم
                    break;
                case 'child':
                    $message->message_child = $message->message;
                    unset($message->message);  // إزالة الحقل القديم
                    break;
                // يمكنك إضافة حالات أخرى هنا إذا لزم الأمر
            }
        }

        return response()->json( $messages);
    }
    public function getMessagesWithData($receiver_id)
    {
        $sender_id = Auth::id();  // الحصول على معرف المستخدم الحالي

        // جلب الرسائل
        $messages = Message::where(function($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $sender_id)->where('receiver_id', $receiver_id);
        })->orWhere(function($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $receiver_id)->where('receiver_id', $sender_id);
        })->get();

        // تعديل الرسائل حسب نوع الحساب
        $messages = $messages->map(function ($message) {
            $sender = User::find($message->sender_id);
            $receiver = User::find($message->receiver_id);

            // تعيين أسماء المرسل والمستقبل
            $message->sender_name = $sender ? ($sender->name ?? 'admin') : 'admin';
            $message->receiver_name = $receiver ? ($receiver->name ?? 'admin') : 'admin';

            // إعداد الرسائل بناءً على نوع الحساب
            if ($sender && $receiver) {
                if ($sender->account_type === 'admin') {
                    $message->message_admin = $message->message;
                    $message->message_child = null;  // مسح رسالة الطفل
                } elseif ($sender->account_type === 'child') {
                    $message->message_admin = null;  // مسح رسالة الادمن
                    $message->message_child = $message->message;
                }
            }

            // إزالة الحقل القديم
            unset($message->message);

            return $message;
        });

        return response()->json(['data' => $messages]);
    }
    public function getAdminConversations()
    {
        $admin_id = Auth::id();  // الحصول على معرف المستخدم الحالي (المفترض أن يكون Admin)

        // جلب المحادثات التي أجراها المسؤول مع الأطفال فقط
        $conversations = Message::where('sender_id', $admin_id)
            ->orWhere('receiver_id', $admin_id)
            ->orderBy('created_at', 'desc')  // ترتيب حسب الأحدث
            ->get()
            ->unique(function ($message) use ($admin_id) {
                // تجميع حسب المحادثة مع كل طفل
                return $message->sender_id == $admin_id ? $message->receiver_id : $message->sender_id;
            });

        // معالجة البيانات لكل محادثة
        $result = $conversations->map(function ($message) use ($admin_id) {
            // تحديد من هو الطفل في المحادثة
            $is_child_sender = $message->sender_id != $admin_id;

            return [
                'child_name' => $is_child_sender ? $message->sender->name : $message->receiver->name,  // اسم الطفل
                'last_message' => $message->message,
                'timestamp' => $message->created_at,
                'user_id' => $is_child_sender ? $message->sender_id : $message->receiver_id,  // جلب ID الطفل
                'sender' => $is_child_sender ? 'child' : 'admin',  // تحديد مصدر الرسالة الأخيرة
            ];
        });

        return response()->json($result);
    }




}
