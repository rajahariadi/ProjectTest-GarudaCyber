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
            'discussion_id' => 'required|exists:discussions,id',
            'content' => 'required',
        ], [
            'discussion_id.required' => 'Discussion tidak boleh kosong',
            'discussion_id.exists' => 'Discussion tidak ditemukan',
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

    public function update(Request $request, $id, $replies_id)
    {
        $reply = Reply::find($replies_id);

        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ], [
            'content.required' => 'Content tidak boleh kosong',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$reply) {
            return response()->json([
                'success' => false,
                'message' => 'Reply tidak ditemukan'
            ], 404);
        }

        if (Auth::user()->id !== $reply->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        try {
            $reply->update([
                'content' => $request->content,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reply berhasil diedit',
                'data' => $reply
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Reply gagal diedit',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id, $replies_id)
    {
        $reply = Reply::find($replies_id);

        if (!$reply) {
            return response()->json([
                'success' => false,
                'message' => 'Reply tidak ditemukan'
            ], 404);
        }

        if (Auth::user()->id !== $reply->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        try {
            $reply->delete();
            return response()->json([
                'success' => true,
                'message' => 'Reply berhasil dihapus',
                'data' => $reply
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Reply gagal dihapus',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
