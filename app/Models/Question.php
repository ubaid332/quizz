<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quizz_id', 'question', 'mandatory'
    ];

    public function answers(){
        return $this->hasMany(Answer::class, 'question_id','id');
      }

    public function quizz(){
        return $this->belongsTo(Quiz::class);
      }

    public $timestamps = false;
}
