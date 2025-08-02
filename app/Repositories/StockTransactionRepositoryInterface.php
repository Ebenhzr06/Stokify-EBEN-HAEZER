<?php

namespace App\Repositories;

interface StockTransactionRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create($data);
    public function update($id,$data);
    public function delete($id);
    public function allWithProductAndUser();

}
