<?php

namespace App\Models;

use App\Http\Controllers\CategoryController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sub_Category extends Model
{
    use HasFactory;

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function product():HasMany
    {
        return $this->hasMany(Product::class);
    }
}
