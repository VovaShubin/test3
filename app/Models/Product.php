<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'image',
        'price'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
