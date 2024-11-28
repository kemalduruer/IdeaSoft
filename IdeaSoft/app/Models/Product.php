<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    protected $fillable = ['name', 'category', 'price', 'stock'];

    public $timestamps = false;

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'productId'); // Foreign key 'productId'
    }
}
