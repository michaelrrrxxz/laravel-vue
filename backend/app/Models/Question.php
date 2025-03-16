<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;
    protected $fillable = [
        "test_type",
        "question",
        "option_a",
        "option_b",
        "option_c",
        "option_d",
        "option_e",
        "option_correct",
        "ctype",
        "isDeleted",
    ] ;
}
