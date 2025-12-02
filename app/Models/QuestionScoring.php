<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionScoring extends Model
{
    use HasFactory;
    protected $table='question_scoring';
    protected $primaryKey='question_id';
    public $incrementing=false;
    protected $fillable=['question_id','max_score','partial_credit','negative_score','rubric'];
    protected $casts=['rubric'=>'array'];
    public function question(){ return $this->belongsTo(Question::class,'question_id'); }
}
