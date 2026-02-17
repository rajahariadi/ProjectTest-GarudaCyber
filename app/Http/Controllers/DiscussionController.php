<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DiscussionController extends Controller
{
    public function discussion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'content' => 'required',
        ], [
            'course_id.required' => 'Course tidak boleh kosong',
            'course_id.exists' => 'Course tidak ditemukan',
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
            $discussion = Discussion::create([
                'course_id' => $request->course_id,
                'user_id' => Auth::user()->id,
                'content' => $request->content,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Discussion berhasil dibuat',
                'data' => $discussion
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Discussion gagal dibuat',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $discussion = Discussion::find($id);

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

        if (!$discussion) {
            return response()->json([
                'success' => false,
                'message' => 'Discussion tidak ditemukan'
            ], 404);
        }

        if (Auth::user()->id !== $discussion->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        try {
            $discussion->update([
                'course_id' => $request->course_id,
                'content' => $request->content,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Discussion berhasil diedit',
                'data' => $discussion
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Discussion gagal diedit',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $discussion = Discussion::find($id);

        if (!$discussion) {
            return response()->json([
                'success' => false,
                'message' => 'Discussion tidak ditemukan'
            ], 404);
        }

        if (Auth::user()->id !== $discussion->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        try {
            $discussion->delete();
            return response()->json([
                'success' => true,
                'message' => 'Discussion berhasil dihapus',
                'data' => $discussion
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Discussion gagal dihapus',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
