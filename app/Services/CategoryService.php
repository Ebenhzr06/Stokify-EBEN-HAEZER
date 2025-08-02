<?php

namespace App\Services;

use App\Repositories\CategoryRepositoryInterface;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CategoryService implements CategoryServiceInterface
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {

        return $this->categoryRepository->getAll();
    }

    public function getCategoryById($id)
    {

        return $this->categoryRepository->findById($id);
    }

    public function createCategory($data)
    {
        $result = $this->categoryRepository->create($data);

        UserActivity::create([
            'entity' => 'category',
            'entity_name' => 'Kategori',
            'message' => 'Membuat kategori baru: ' . ($data['name'] ?? '[tidak diketahui]'),
        ]);

        return $result;
    }

    public function updateCategory($id, $data)
    {
        $result = $this->categoryRepository->update($id, $data);

        return $result;
    }

    public function deleteCategory($id)
    {
        $result = $this->categoryRepository->delete($id);

        UserActivity::create([
            'entity' => 'category',
            'entity_name' => 'Kategori',
            'message' => 'Menghapus kategori ID ' . $id,
        ]);

        return $result;
    }
}
