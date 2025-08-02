<?php

namespace App\Services;

interface SupplierServiceInterface
{
    public function getAllSuppliers();
    public function getSupplierById($id);
    public function createSupplier( $data);
    public function updateSupplier($id,  $data);
    public function deleteSupplier($id);
}
