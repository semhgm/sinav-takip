<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['title','duration_minutes','created_by','settings'];
    protected $casts = ['settings'=>'array'];

    public function creator(){ return $this->belongsTo(User::class,'created_by'); }
    public function questions(){ return $this->belongsToMany(Question::class,'exam_question')->withPivot('order'); }
    public function sessions(){ return $this->hasMany(ExamSession::class); }
    public function assignments(){ return $this->hasMany(ExamAssignment::class); }
}
