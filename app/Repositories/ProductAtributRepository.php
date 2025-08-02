<?php

namespace App\Repositories;

use App\Models\ProductAtribut;

class ProductAtributRepository implements ProductAtributRepositoryInterface
{
    public function getAll()
    {
        return ProductAtribut::all();
    }

    public function findById($id)
    {
        return ProductAtribut::findOrFail($id);
    }

    public function create( $data)
    {
        return ProductAtribut::create($data);
    }

    public function update($id,  $data)
    {
        $atribut = ProductAtribut::findOrFail($id);
        $atribut->update($data);
        return $atribut;
    }

    public function delete($id)
    {
        $atribut = ProductAtribut::findOrFail($id);
        return $atribut->delete();
    }
}
