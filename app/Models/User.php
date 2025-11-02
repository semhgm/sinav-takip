<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['name','email','password','role'];
    protected $hidden = ['password','remember_token'];

    public function createdExams(){ return $this->hasMany(Exam::class,'created_by'); }
    public function sessions(){ return $this->hasMany(ExamSession::class); }
    public function consents(){ return $this->hasMany(UserConsent::class); }
}
