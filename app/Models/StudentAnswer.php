<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = ['session_id','question_id','answer_text','is_correct','score'];

    public function session(){ return $this->belongsTo(ExamSession::class,'session_id'); }
    public function question(){ return $this->belongsTo(Question::class,'question_id'); }
    public function manualGradings(){ return $this->hasMany(ManualGrading::class,'answer_id'); }
    public function scoreChanges(){ return $this->hasMany(ScoreChangeHistory::class,'answer_id'); }
}
