<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAssignment extends Model
{
    use HasFactory;
    protected $fillable=['exam_id','user_id','starts_at','ends_at','extra_minutes','status'];
    protected $dates=['starts_at','ends_at'];
    public function exam(){ return $this->belongsTo(Exam::class); }
    public function user(){ return $this->belongsTo(User::class); }
}
