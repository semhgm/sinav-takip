<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConsent extends Model
{
    use HasFactory;
    protected $fillable=['user_id','subject','is_approved','approved_at','ip','user_agent','is_valid'];
    protected $dates=['approved_at'];
    public function user(){ return $this->belongsTo(User::class); }
}
