<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name', 'price', 'price_after_discount', 'units', 'description', 'slug'
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
