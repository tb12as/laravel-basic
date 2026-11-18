<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseFactory(ProductFactory::class)]
#[Fillable('name', 'price', 'stock')]
class Product extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'name',
    //     'price',
    //     'stock',
    // ];
}
