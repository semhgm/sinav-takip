<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    protected $fillable = ['user_id','exam_id','started_at','ended_at','score','status','proctor_token'];
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
    protected $dates = ['started_at','ended_at'];

    public function user(){ return $this->belongsTo(User::class); }
    public function exam(){ return $this->belongsTo(Exam::class); }
    public function studentAnswers(){ return $this->hasMany(StudentAnswer::class,'session_id'); }
    public function events(){ return $this->hasMany(SessionEvent::class,'session_id'); }
    public function violations(){ return $this->hasMany(Violation::class,'session_id'); }
    public function deviceFingerprints(){ return $this->hasMany(DeviceFingerprint::class,'session_id'); }
    public function accessTokens(){ return $this->hasMany(ExamAccessToken::class,'session_id'); }
    public function heartbeats(){ return $this->hasMany(SessionHeartbeat::class,'session_id'); }
    public function summary(){ return $this->hasOne(SessionSummary::class,'session_id'); }

}
