<?php

namespace App\Repositories;

use App\Models\StockTransaction;

class StockTransactionRepository implements StockTransactionRepositoryInterface
{
    public function getAll()
    {
        return StockTransaction::all();
    }

    public function findById($id)
    {
        return StockTransaction::findOrFail($id);
    }

    public function create($data)
    {
        return StockTransaction::create($data);
    }

    public function update($id, $data)
    {
        $transaction = StockTransaction::findOrFail($id);
        $transaction->update($data);
        return $transaction;
    }

    public function delete($id)
    {
        $transaction = StockTransaction::findOrFail($id);
        return $transaction->delete();
    }
    public function allWithProductAndUser()
    {
        return StockTransaction::with(['product', 'user'])->get();
    }
}
