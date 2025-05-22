<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'customer',
        'status',
        'comment',
        'product_id',
        'count'
    ];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getStatusAttribute($value)
    {
        $arr = [
            0 => 'new',
            1 => 'completed',
        ];
        return $arr[$value];
    }

    public function getPriceAttribute()
    {
        return ($this->count * $this->product->price);
    }
}
