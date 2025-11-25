<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductProperty extends Model
{
    use HasFactory;

    public $table = 'products-properties';

    protected $fillable = [
        'productId',
        'propertyId',
        'value'
    ];
}
