<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function sub_category():BelongsTo
    {
        return $this->belongsTo(Sub_Category::class);
    }

    public function product_image():HasMany
    {
        return $this->hasMany(Product_Image::class);
    }

    public function book_mark():HasMany
    {
        return $this->HasMany(BookMark::class);
    }
}
