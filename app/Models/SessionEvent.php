<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionEvent extends Model
{
    public $timestamps = false;
    protected $fillable=['session_id','source','event_type','details','level','occurred_at','created_at'];
    protected $casts=['details'=>'array'];
    protected $dates=['occurred_at','created_at'];
    public function session(){ return $this->belongsTo(ExamSession::class,'session_id'); }}
