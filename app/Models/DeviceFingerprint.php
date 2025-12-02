<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceFingerprint extends Model
{
    use HasFactory;
    protected $fillable=['session_id','ip','user_agent','screen_resolution','os','browser','extra'];
    protected $casts=['extra'=>'array'];
    public function session(){ return $this->belongsTo(ExamSession::class,'session_id'); }
}
