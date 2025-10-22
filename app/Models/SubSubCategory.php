<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubSubCategory extends Model
{
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
