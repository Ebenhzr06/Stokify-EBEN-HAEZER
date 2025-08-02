<?php
namespace App\Repositories;

use App\Models\StockTransaction;
use App\Repositories\StockOpnameRepositoryInterface;

class StockOpnameRepository implements StockOpnameRepositoryInterface
{
    public function getAll()
    {
        return StockTransaction::with(['product', 'user'])->latest()->get();
    }

    public function findById($id)
    {
        return StockTransaction::with(['product', 'user'])->findOrFail($id);
    }

    public function create( $data)
    {
        return StockTransaction::create($data);
    }

    public function update($id, $data)
    {
        $stockOpname = StockTransaction::findOrFail($id);
        $stockOpname->update($data);
        return $stockOpname;
    }

    public function delete($id)
    {
        return StockTransaction::destroy($id);
    }
}
