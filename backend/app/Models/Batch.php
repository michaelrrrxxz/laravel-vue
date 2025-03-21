<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    /** @use HasFactory<\Database\Factories\BatchFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "access_key",
        "status"
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($batch) {
            if (empty($batch->status)) {
                $batch->status = 'active';
            }
        });
    }
}
