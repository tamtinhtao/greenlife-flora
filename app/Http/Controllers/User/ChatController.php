<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | USER GỬI TIN NHẮN CHO ADMIN
    |--------------------------------------------------------------------------
    */

    public function send(Request $request)
    {
        $request->validate(
            [
                'message' =>
                    'required|string|max:2000',
            ],
            [
                'message.required' =>
                    'Nội dung tin nhắn không được để trống.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | TÌM ADMIN NHẬN TIN
        |--------------------------------------------------------------------------
        */

        $admin = User::where(
            'role',
            'admin'
        )->first();


        if (!$admin) {

            return response()->json(
                [
                    'error' =>
                        'Hiện chưa có Admin để nhận tin nhắn.',
                ],
                404
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TẠO TIN NHẮN
        |--------------------------------------------------------------------------
        */

        $message = Message::create([
            'sender_id' =>
                Auth::id(),

            'receiver_id' =>
                $admin->id,

            'content' =>
                trim($request->message),

            'is_read' =>
                false,
        ]);


        /*
        |--------------------------------------------------------------------------
        | LOAD NGƯỜI GỬI
        |--------------------------------------------------------------------------
        */

        $message->load(
            'sender'
        );


        return response()->json(
            [
                'success' =>
                    true,

                'message' =>
                    $message,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LẤY LỊCH SỬ CHAT GIỮA USER HIỆN TẠI VÀ ADMIN
    |--------------------------------------------------------------------------
    */

    public function getMessages()
    {
        $userId =
            Auth::id();


        $admin = User::where(
            'role',
            'admin'
        )->first();


        if (!$admin) {

            return response()->json(
                []
            );
        }


        $adminId =
            $admin->id;


        /*
        |--------------------------------------------------------------------------
        | USER -> ADMIN
        | HOẶC
        | ADMIN -> USER
        |--------------------------------------------------------------------------
        */

        $messages =
            Message::with([
                'sender:id,name',
                'receiver:id,name',
            ])

            ->where(
                function ($query) use (
                    $userId,
                    $adminId
                ) {

                    $query
                        ->where(
                            'sender_id',
                            $userId
                        )
                        ->where(
                            'receiver_id',
                            $adminId
                        );
                }
            )

            ->orWhere(
                function ($query) use (
                    $userId,
                    $adminId
                ) {

                    $query
                        ->where(
                            'sender_id',
                            $adminId
                        )
                        ->where(
                            'receiver_id',
                            $userId
                        );
                }
            )

            ->orderBy(
                'created_at',
                'asc'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | ĐÁNH DẤU TIN ADMIN GỬI CHO USER LÀ ĐÃ ĐỌC
        |--------------------------------------------------------------------------
        */

        Message::where(
            'sender_id',
            $adminId
        )
            ->where(
                'receiver_id',
                $userId
            )
            ->where(
                'is_read',
                false
            )
            ->update([
                'is_read' =>
                    true,
            ]);


        return response()->json(
            $messages
        );
    }
}