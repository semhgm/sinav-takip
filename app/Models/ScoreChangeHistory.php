<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreChangeHistory extends Model
{
    use HasFactory;
    protected $table='score_change_history';
    protected $fillable=['answer_id','old_score','new_score','reason','acted_by_id'];
    public function answer(){ return $this->belongsTo(StudentAnswer::class,'answer_id'); }
    public function actor(){ return $this->belongsTo(User::class,'acted_by_id'); }
}
