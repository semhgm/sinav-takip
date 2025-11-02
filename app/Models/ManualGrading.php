<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualGrading extends Model
{
    use HasFactory;
    protected $table='manual_grading';
    protected $fillable=['answer_id','grader_id','score','notes'];
    public function answer(){ return $this->belongsTo(StudentAnswer::class,'answer_id'); }
    public function grader(){ return $this->belongsTo(User::class,'grader_id'); }
}
