<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create($data);
    public function update($id,$data);
    public function delete($id);
}
