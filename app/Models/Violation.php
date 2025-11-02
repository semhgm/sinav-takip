<?php // Violation.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Violation extends Model {
    protected $fillable=['session_id','type','occurred_at','source'];
    protected $dates=['occurred_at'];
    public function session(){ return $this->belongsTo(ExamSession::class,'session_id'); }
}
