<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiProduct extends Model
{
        protected $fillable = [
            'company',
            'url',
            'description',
            'status',
            'created_by',
            'updated_by'
        ];
}
