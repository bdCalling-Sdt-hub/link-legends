<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carosal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'description',
        'image',
        'url',
        'brand_name'
    ];
}
