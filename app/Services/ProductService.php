<?php

namespace App\Services;

use App\Models\Product;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Repositories\ProductRepositoryInterface;
use App\Services\ProductServiceInterface;
use Carbon\Carbon;


class ProductService implements ProductServiceInterface
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return Product::with(['category', 'supplier'])->paginate(20);
    }

    public function getProductById($id)
    {
        return $this->productRepository->findById($id);
    }

    public function createProduct($data, $image)
    {
        if ($image) {
            $data['image'] = $image->store('product-images', 'public');
        }

        $product = $this->productRepository->create($data);


        return $product;
    }

    public function updateProduct($id, $data, $image)
    {
        if ($image) {
            $data['image'] = $image->store('product-images', 'public');
        }

        $product = $this->productRepository->update($id, $data);


        return $product;
    }

    public function deleteProduct($id)
    {

        return $this->productRepository->delete($id);
    }

    protected function logActivity($message, $entityId, $entityName)
    {
        UserActivity::create([
            'user_id'     => Auth::id(),
            'role'        => Auth::user()->role ?? 'unknown',
            'entity'      => 'product',
            'entity_id'   => $entityId,
            'entity_name' => $entityName,
            'message'     => $message,
            'address'     => Request::ip(),
        ]);
    }

    public function getTotalProducts()
    {
        return Product::count();
    }

    public function getTotalProductsBeforeDate(Carbon $date): int
{
    return Product::where('created_at', '<', $date)->count();
}
}
