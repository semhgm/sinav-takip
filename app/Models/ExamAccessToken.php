<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAccessToken extends Model
{
    use HasFactory;
    protected $fillable=['session_id','token','is_valid','last_accessed_at'];
    protected $dates=['last_accessed_at'];
    public function session(){ return $this->belongsTo(ExamSession::class,'session_id'); }
}
