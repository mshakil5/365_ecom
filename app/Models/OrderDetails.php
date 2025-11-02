<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderCustomisations()
    {
        return $this->hasMany(OrderCustomisation::class, 'order_details_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
