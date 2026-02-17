<?php

namespace App\Http\Controllers;

use App\Mail\SubmissionScore;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    public function submission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assignment_id' => 'required|exists:assignments,id',
            'file_path' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ], [
            'assignment_id.required' => 'Assignment tidak boleh kosong',
            'assignment_id.exists' => 'Assignment tidak ditemukan',
            'file_path.required' => 'File tidak boleh kosong',
            'file_path.mimes' => 'Format File tidak valid (pdf,doc,docx)',
            'file_path.max' => 'File tidak boleh lebih dari 5 MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $assignment = Assignment::find($request->assignment_id);
            $user = Auth::user();

            $file = $request->file('file_path');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($assignment->title) . '_' . Str::slug($user->name) . '.' . $extension;
            $path = Storage::disk('public')->putFileAs('submissions', $file, $filename);

            $assignment = Submission::create([
                'assignment_id' => $request->assignment_id,
                'student_id' => $user->id,
                'file_path' => $path,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Submission berhasil diunggah',
                'data' => $assignment
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Submission gagal diunggah',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function grade(Request $request, $id)
    {
        $submission = Submission::find($id);
        $validator = Validator::make($request->all(), [
            'score' => 'required|integer|between:0,100',
        ], [
            'score.required' => 'Score tidak boleh kosong',
            'score.integer' => 'Score tidak valid',
            'score.between' => 'Jarak score harus antara 0 dan 100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors()
            ], 422);
        }


        try {
            $submission->update([
                'score' => $request->score,
            ]);

            $student = $submission->student;
            $course = $submission->assignment->course;
            $assignmentTitle = $submission->assignment->title;

            Mail::to($student->email)->send(new SubmissionScore(
                $student,
                $course,
                $assignmentTitle,
                $request->score
            ));

            return response()->json([
                'success' => true,
                'message' => 'Grade berhasil diberikan',
                'data' => $submission
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Grade gagal diberikan',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
