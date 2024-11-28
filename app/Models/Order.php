<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = ['customerId','total'];
    public $timestamps = false;


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerId');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'orderId');
    }

}
