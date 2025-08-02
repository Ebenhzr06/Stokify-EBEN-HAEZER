<?php

namespace App\Services;

interface ProductServiceInterface
{
    public function getAllProducts();
    public function getProductById($id);
    public function createProduct($data,$image);
    public function updateProduct($id,$data,$image);
    public function deleteProduct($id);

}
