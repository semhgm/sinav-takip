<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MongoCorrelation extends Model
{
    use HasFactory;
    protected $fillable=['session_id','mongo_log_key'];
    public function session(){ return $this->belongsTo(ExamSession::class,'session_id'); }

}
