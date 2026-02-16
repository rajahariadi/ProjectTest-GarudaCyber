<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    public function assignment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'deadline' => 'required|date',
        ], [
            'course_id.required' => 'Course tidak boleh kosong',
            'title.required' => 'Title tidak boleh kosong',
            'description.required' => 'Description tidak boleh kosong',
            'deadline.required' => 'Deadline tidak boleh kosong',
            'deadline.date' => 'Deadline tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $assignment = Assignment::create([
                'course_id' => $request->course_id,
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assignment berhasil dibuat',
                'data' => $assignment
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment gagal dibuat',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
