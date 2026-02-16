<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index()
    {
        try {
            $courses = Course::with('lecturers')->get();

            return response()->json([
                'success' => true,
                'message' => 'Course berhasil diambil',
                'data' => $courses
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Course gagal diambil',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama tidak boleh kosong',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $course = Course::create([
                'name' => $request->name,
                'description' => $request->description,
                'lecturer_id' => Auth::user()->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course berhasil dibuat',
                'data' => $course
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Course gagal dibuat',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama tidak boleh kosong',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course tidak ditemukan'
            ], 404);
        }

        try {
            $course->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course berhasil diedit',
                'data' => $course
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Course gagal diedit',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {

        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course tidak ditemukan'
            ], 404);
        }

        try {
            $course->delete();
            return response()->json([
                'success' => true,
                'message' => 'Course berhasil dihapus',
                'data' => $course
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Course gagal dihapus',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function enroll($id)
    {
        $course = Course::find($id);

        $alreadyEnrolled = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $id)
            ->exists();

        if ($alreadyEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'Sudah mendaftar pada course ini'
            ], 409);
        }

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course tidak ditemukan'
            ], 409);
        }

        try {
            $enrollment = Enrollment::create([
                'student_id' => Auth::id(),
                'course_id' => $course->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course berhasil didaftarkan',
                'data' => $enrollment
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Course gagal didaftarkan',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
