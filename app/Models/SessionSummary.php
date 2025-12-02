<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionSummary extends Model
{
    use HasFactory;
    protected $table='session_summaries';
    protected $primaryKey='session_id';
    public $incrementing=false;
    protected $fillable=['session_id','integrity_score','total_events','critical_events','browser_events','camera_events','notes'];
    public function session(){ return $this->belongsTo(ExamSession::class,'session_id'); }
}
