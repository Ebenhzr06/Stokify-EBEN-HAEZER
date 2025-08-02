<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAtribut extends Model
{
    use HasFactory;

    protected $table = 'product_atributs';

    protected $fillable = [
        'product_id',
        'name',
        'value',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
