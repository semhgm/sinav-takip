<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['category_id','type','text','options','correct_option','feedback','points'];
    protected $casts = ['options'=>'array'];


    public function category(){ return $this->belongsTo(QuestionCategory::class,'category_id'); }
    public function exams(){ return $this->belongsToMany(Exam::class,'exam_question')->withPivot('order'); }
    public function scoring(){ return $this->hasOne(QuestionScoring::class,'question_id'); }
}
