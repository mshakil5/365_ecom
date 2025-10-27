<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

     public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function apiLog()
    {
        return $this->belongsTo(ApiLog::class, 'api_log_id');
    }

    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first()?->image_path;
    }

    public function getColorsAttribute()
    {
        return $this->variants()->with('color')->get()->pluck('color')->unique();
    }

    public function getSizesAttribute()
    {
        return $this->variants()->with('size')->get()->pluck('size')->unique();
    }
}
