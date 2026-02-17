<?php

namespace App\Http\Controllers;

use App\Mail\NewAssignment;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    public function assignment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required',
            'description' => 'required',
            'deadline' => 'required|date|after:now',
        ], [
            'course_id.required' => 'Course tidak boleh kosong',
            'course_id.exists' => 'Course tidak ditemukan',
            'title.required' => 'Title tidak boleh kosong',
            'description.required' => 'Description tidak boleh kosong',
            'deadline.required' => 'Deadline tidak boleh kosong',
            'deadline.date' => 'Deadline tidak valid',
            'deadline.after' => 'Deadline tidak boleh kurang dari sekarang',
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

            $course = Course::find($request->course_id);
            $students = $course->students;

            foreach ($students as $student) {
                Mail::to($student->email)->send(new NewAssignment(
                    $course,
                    $student,
                    $assignment->title
                ));
            }

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

    public function update(Request $request, $id)
    {
        $assignment = Assignment::find($id);

        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required',
            'description' => 'required',
            'deadline' => 'required|date|after:now',
        ], [
            'course_id.required' => 'Course tidak boleh kosong',
            'course_id.exists' => 'Course tidak ditemukan',
            'title.required' => 'Title tidak boleh kosong',
            'description.required' => 'Description tidak boleh kosong',
            'deadline.required' => 'Deadline tidak boleh kosong',
            'deadline.date' => 'Deadline tidak valid',
            'deadline.after' => 'Deadline tidak boleh kurang dari sekarang',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment tidak ditemukan'
            ], 404);
        }

        try {
            $assignment->update([
                'course_id' => $request->course_id,
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assignment berhasil diedit',
                'data' => $assignment
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment gagal diedit',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $assignment = Assignment::find($id);

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment tidak ditemukan'
            ], 404);
        }

        try {
            $assignment->delete();
            return response()->json([
                'success' => true,
                'message' => 'Assignment berhasil dihapus',
                'data' => $assignment
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment gagal dihapus',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
