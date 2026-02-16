<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_path',
        'score'
    ];

    public function assignments()
    {
        return $this->belongsTo(Assignment::class);
    }
    public function students()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
