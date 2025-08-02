<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll()
    {
        return Product::with(['category', 'supplier'])->get();
    }

    public function findById($id)
    {
        return Product::with(['category', 'supplier', 'productAtributs'])->findOrFail($id);
    }

    public function create($data)
    {
        return Product::create($data);
    }

    public function update($id, $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        return Product::destroy($id);
    }
}
