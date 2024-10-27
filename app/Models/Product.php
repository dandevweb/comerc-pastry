<?php

namespace App\Models;

use App\Enums\ProductTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'type' => ProductTypeEnum::class,
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_product');
    }

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? number_format($value / 100, 2, '.', '') : 0,
            set: fn ($value) => $value ? $value * 100 : 0,
        );
    }
}
