<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DANH SÁCH USER ĐÃ CHAT VỚI ADMIN
    |--------------------------------------------------------------------------
    */

    public function getUsers()
    {
        $adminId = Auth::id();


        /*
        |--------------------------------------------------------------------------
        | LẤY TẤT CẢ USER ID TỪ CÁC CUỘC HỘI THOẠI
        |--------------------------------------------------------------------------
        */

        $userIds = Message::where(
            'receiver_id',
            $adminId
        )
            ->orWhere(
                'sender_id',
                $adminId
            )
            ->orderByDesc(
                'created_at'
            )
            ->get()
            ->map(
                function ($message) use ($adminId) {

                    return
                        $message->sender_id == $adminId
                            ? $message->receiver_id
                            : $message->sender_id;
                }
            )
            ->unique()
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | LẤY THÔNG TIN USER
        |--------------------------------------------------------------------------
        */

        $users = User::whereIn(
            'id',
            $userIds
        )
            ->where(
                'role',
                'user'
            )
            ->select(
                'id',
                'name',
                'email'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ĐẾM TIN CHƯA ĐỌC
        |--------------------------------------------------------------------------
        */

        $users->each(
            function ($user) use ($adminId) {

                $user->unread_count =
                    Message::where(
                        'sender_id',
                        $user->id
                    )
                        ->where(
                            'receiver_id',
                            $adminId
                        )
                        ->where(
                            'is_read',
                            false
                        )
                        ->count();
            }
        );


        return response()->json(
            $users
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LẤY TIN NHẮN CỦA MỘT USER
    |--------------------------------------------------------------------------
    */

    public function getMessages($userId)
    {
        $adminId =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA USER CÓ TỒN TẠI
        |--------------------------------------------------------------------------
        */

        $user = User::where(
            'id',
            $userId
        )
            ->where(
                'role',
                'user'
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | LẤY HỘI THOẠI 2 CHIỀU
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
        | ADMIN ĐÃ MỞ CHAT
        | → ĐÁNH DẤU TIN USER GỬI LÀ ĐÃ ĐỌC
        |--------------------------------------------------------------------------
        */

        Message::where(
            'sender_id',
            $userId
        )
            ->where(
                'receiver_id',
                $adminId
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


    /*
    |--------------------------------------------------------------------------
    | ADMIN GỬI TIN CHO USER
    |--------------------------------------------------------------------------
    */

    public function send(Request $request)
    {
        $request->validate(
            [
                'user_id' =>
                    'required|exists:users,id',

                'message' =>
                    'required|string|max:2000',
            ],
            [
                'user_id.required' =>
                    'Vui lòng chọn khách hàng.',

                'message.required' =>
                    'Nội dung tin nhắn không được để trống.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | CHỈ CHO GỬI CHO ROLE USER
        |--------------------------------------------------------------------------
        */

        $user = User::where(
            'id',
            $request->user_id
        )
            ->where(
                'role',
                'user'
            )
            ->first();


        if (!$user) {

            return response()->json(
                [
                    'error' =>
                        'Không tìm thấy người dùng.',
                ],
                404
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LƯU TIN NHẮN
        |--------------------------------------------------------------------------
        */

        $message =
            Message::create([
                'sender_id' =>
                    Auth::id(),

                'receiver_id' =>
                    $user->id,

                'content' =>
                    trim(
                        $request->message
                    ),

                /*
                 * User chưa đọc tin này.
                 */
                'is_read' =>
                    false,
            ]);


        $message->load(
            'sender:id,name'
        );


        return response()->json([
            'success' =>
                true,

            'message' =>
                $message,
        ]);
    }
}