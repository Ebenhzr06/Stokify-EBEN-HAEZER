<?php

namespace App\Services;

interface StockTransactionServiceInterface
{
    public function getAll();
    public function findById($id);
    public function create( $data);
    public function update($id,  $data);
    public function delete($id);
    public function getAllProductAndUser();
}
