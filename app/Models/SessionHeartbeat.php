<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionHeartbeat extends Model
{
    use HasFactory;
    protected $fillable=['session_id','beat_at','latency_ms'];
    protected $dates=['beat_at'];
    public function session(){ return $this->belongsTo(ExamSession::class,'session_id'); }

}
