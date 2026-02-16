<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReplyController extends Controller
{
    public function reply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discussion_id' => 'required',
            'content' => 'required',
        ], [
            'discussion_id.required' => 'Discussion tidak boleh kosong',
            'content.required' => 'Content tidak boleh kosong',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $reply = Reply::create([
                'discussion_id' => $request->discussion_id,
                'user_id' => Auth::user()->id,
                'content' => $request->content,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reply berhasil dibuat',
                'data' => $reply
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Reply gagal dibuat',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
