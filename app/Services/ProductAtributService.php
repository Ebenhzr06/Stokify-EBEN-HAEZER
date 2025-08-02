<?php

namespace App\Services;

use App\Repositories\ProductAtributRepositoryInterface;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ProductAtributService implements ProductAtributServiceInterface
{
    protected $productAtributRepository;

    public function __construct(ProductAtributRepositoryInterface $productAtributRepository)
    {
        $this->productAtributRepository = $productAtributRepository;
    }

    public function getAll()
    {
        return $this->productAtributRepository->getAll();
    }

    public function find($id)
    {
        return $this->productAtributRepository->findById($id);
    }

    public function create($data)
    {
        $result = $this->productAtributRepository->create($data);

        $this->logActivity('Menambahkan atribut produk', $result->id, $result->name ?? 'Atribut Produk');

        return $result;
    }

    public function update($id, $data)
    {
        $result = $this->productAtributRepository->update($id, $data);

        $atribut = $this->productAtributRepository->findById($id);
        $this->logActivity('Mengubah atribut produk', $id, $atribut->name ?? 'Atribut Produk');

        return $result;
    }

    public function delete($id)
    {
        $atribut = $this->productAtributRepository->findById($id);
        $this->logActivity('Menghapus atribut produk', $id, $atribut->name ?? 'Atribut Produk');

        return $this->productAtributRepository->delete($id);
    }

    protected function logActivity($message, $entityId, $entityName)
    {
        UserActivity::create([
            'user_id'     => Auth::id(),
            'role'        => Auth::user()->role ?? 'unknown',
            'entity'      => 'product_atribut',
            'entity_id'   => $entityId,
            'entity_name' => $entityName,
            'message'     => $message,
            'address'     => Request::ip(),
        ]);
    }
}
