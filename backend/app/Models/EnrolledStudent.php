<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrolledStudent extends Model
{
    /** @use HasFactory<\Database\Factories\Api\EnrolledStudentFactory> */
    use HasFactory;
    protected $fillable = [
        "name",
        "course",
        "id_number",
        "gender",
    ] ;
}
