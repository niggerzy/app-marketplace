<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'sku',
        'price',
        'cost',
        'discount_price',
        'stock',
        'min_stock',
        'image',
        'images',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'images' => 'json',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getDiscountedPriceAttribute(): float
    {
        return $this->discount_price ?? $this->price;
    }

    public function getDiscountPercentAttribute(): float
    {
        if (!$this->discount_price) {
            return 0;
        }

        return round((($this->price - $this->discount_price) / $this->price) * 100, 2);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
