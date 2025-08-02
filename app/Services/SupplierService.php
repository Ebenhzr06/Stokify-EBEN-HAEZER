<?php

namespace App\Services;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Repositories\SupplierRepositoryInterface;

class SupplierService implements SupplierServiceInterface
{
    protected $supplierRepository;

    public function __construct(SupplierRepositoryInterface $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    public function getAllSuppliers()
    {
        return $this->supplierRepository->getAll();
    }

    public function getSupplierById($id)
    {
        return $this->supplierRepository->findById($id);
    }

    public function createSupplier($data)
    {
        $supplier = $this->supplierRepository->create($data);

        $this->logActivity('Menambahkan supplier', $supplier->id);

        return $supplier;
    }

    public function updateSupplier($id, $data)
    {
        $this->logActivity('Mengubah supplier', $id);

        return $this->supplierRepository->update($id, $data);
    }

    public function deleteSupplier($id)
    {
        $this->logActivity('Menghapus supplier', $id);

        return $this->supplierRepository->delete($id);
    }

    protected function logActivity($message, $entityId)
    {
        UserActivity::create([
            'user_id'     => Auth::id(),
            'role'        => Auth::user()->role ?? 'unknown',
            'entity'      => 'supplier',
            'entity_id'   => $entityId,
            'entity_name' => 'Supplier',
            'message'     => $message,
            'address'     => Request::ip(),
        ]);
    }
}
