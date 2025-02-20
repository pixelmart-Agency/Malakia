<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'axis_question_id',
        'answer',
    ];


    public function axisQuestion()
    {
        return $this->belongsTo(AxisQuestion::class);
    }


}
