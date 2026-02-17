<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function course()
    {
        try {
            $courses = Course::withCount('students')->get();

            return response()->json([
                'success' => true,
                'message' => 'Statistik course berhasil diambil',
                'data' => $courses
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Statistik course gagal diambil',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function assignment()
    {
        try {
            $total = Submission::all()->count();
            $scored = Submission::whereNotNull('score')->count();
            $unscored = Submission::whereNull('score')->count();

            return response()->json([
                'success' => true,
                'message' => 'Statistik submission berhasil diambil',
                'data' => [
                    'total' => 'Total submission ' . $total,
                    'scored' => 'Total submission sudah dinilai ' . $scored,
                    'unscored' => 'Total submission belum dinilai ' . $unscored,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Statistik submission gagal diambil',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function student($id)
    {
        try {
            $student = User::find($id);

            if ($student->role !== 'student') {
                return response()->json([
                    'success' => false,
                    'message' => 'Student Id tidak valid',
                ], 422);
            }

            $totalAssignments = Submission::where('student_id', $id)->count();
            $avgScore = Submission::where('student_id', $id)->avg('score');
            $sumScore = Submission::where('student_id', $id)->sum('score');
            $listCourses = $student->courses->pluck('name');

            return response()->json([
                'success' => true,
                'message' => 'Statistik student berhasil diambil',
                'data' => [
                    'student' => $student->name,
                    'total_assignments' => $totalAssignments,
                    'average_score' => round($avgScore, 2),
                    'total_score' => $sumScore,
                    'list_course' => $listCourses
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Statistik student gagal diambil',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
