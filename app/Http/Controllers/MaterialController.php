<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'title' => 'required',
            'file_path' => 'required|file|mimes:pdf,doc,docx|max:5120'
        ], [
            'course_id.required' => 'Course tidak boleh kosong',
            'title.required' => 'Title tidak boleh kosong',
            'file_path.required' => 'File tidak boleh kosong',
            'file_path.mimes' => 'Format File tidak valid (pdf,doc,docx)',
            'file_path.max' => 'File tidak boleh lebih dari 5 MB'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file_path');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::slug($request->title) . '.' . $extension;
            $path = Storage::disk('public')->putFileAs('materials', $file, $fileName);

            $material = Material::create([
                'course_id' => $request->course_id,
                'title' => $request->title,
                'file_path' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Material berhasil diupload',
                'data' => $material
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Material gagal diupload',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function download($id)
    {
        try {
            $material = Material::findOrFail($id);

            if (!Storage::disk('public')->exists($material->file_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan'
                ], 404);
            }

            return Storage::disk('public')->download($material->file_path);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal download file',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
