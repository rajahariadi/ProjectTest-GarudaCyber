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
            'course_id' => 'required',
            'content' => 'required',
        ], [
            'course_id.required' => 'Course tidak boleh kosong',
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
}
