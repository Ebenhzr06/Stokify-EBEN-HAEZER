<?php

namespace App\Repositories;

use App\Models\Supplier;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function getAll()
    {
        return Supplier::all();
    }

    public function findById($id)
    {
        return Supplier::findOrFail($id);
    }

    public function create($data)
    {
        return Supplier::create($data);
    }

    public function update($id,$data)
    {
        return Supplier::findOrFail($id)->update($data);
    }

    public function delete($id)
    {
        return Supplier::findOrFail($id)->delete();
    }
}
