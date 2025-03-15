<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        "name",
        "description",
        "stock",
        "image",
        "price"
    ] ;
    public function users()
    {
        return $this->hasOne(User::class);
    }



}
