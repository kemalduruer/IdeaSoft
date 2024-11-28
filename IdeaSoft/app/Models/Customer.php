<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'since', 'revenue'];
    public $timestamps = false;


    public function orders()
    {
        return $this->hasMany(Order::class, 'customerId');
    }
}
