<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_products', 'product_id', 'category_id');
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'category_products', 'product_id', 'sub_category_id');
    }

    public function subSubCategories()
    {
        return $this->belongsToMany(SubSubCategory::class, 'category_products', 'product_id', 'sub_sub_category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
