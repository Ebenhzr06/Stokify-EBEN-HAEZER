<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'sku',
        'description',
        'purchase_price',
        'selling_price',
        'image',
        'minimum_stock'
    ];

public function category()
{
    return $this->belongsTo(Category::class);
}

public function supplier()
{
    return $this->belongsTo(Supplier::class);
}

public function productattributes()
{
    return $this->hasMany(ProductAtribut::class, 'product_id');
}
public function productAtributs()
{
    return $this->hasMany(ProductAtribut::class, 'product_id');
}


}
