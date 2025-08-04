<?php

namespace App\Http\Controllers;

use App\Services\CategoryServiceInterface;
use App\Services\UserActivityService; // Tambahkan ini: Import UserActivityService
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini: Untuk mendapatkan Auth::user()

class CategoryController extends Controller
{
    protected $categoryService;
    protected $userActivityService; // Deklarasikan properti untuk UserActivityService

    // Modifikasi konstruktor untuk meng-inject UserActivityService
    public function __construct(CategoryServiceInterface $categoryService, UserActivityService $userActivityService)
    {
        $this->categoryService = $categoryService;
        $this->userActivityService = $userActivityService;
    }

    public function categorys()
    {
        $categorys = $this->categoryService->getAllCategories();

        // Tidak mencatat aktivitas di sini sesuai permintaan
        return view('example.content.admin.category.index', compact('categorys'));
    }

    public function tambah()
    {
        // Tidak mencatat aktivitas di sini sesuai permintaan
        return view('example.content.admin.category.form');
    }

    public function simpan(Request $request)
    {
        $data = $request->only(['name', 'description']);

        // Asumsi createCategory mengembalikan instance kategori yang baru dibuat
        $category = $this->categoryService->createCategory($data);

        // Catat aktivitas: Menambah kategori baru
        if ($category) { // Pastikan kategori berhasil dibuat sebelum dicatat
            $this->userActivityService->logActivity(
                Auth::user()->name . ' menambahkan kategori baru: ' . $category->name,
                $category->id, // entity_id
                'Kategori', // entity: Tipe entitas (misal: 'Kategori', 'Produk', 'Supplier')
                $category->name // entity_name: Nama spesifik dari entitas
            );
        }

        return redirect()->route('Admin.category.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $category = $this->categoryService->getCategoryById($id);

        // Tidak mencatat aktivitas di sini sesuai permintaan
        return view('example.content.admin.category.form', compact('category'));
    }

    public function update($id, Request $request)
    {
        $data = $request->only(['name', 'description']);

        // Ambil kategori sebelum diupdate untuk mendapatkan nama lamanya
        $oldCategory = $this->categoryService->getCategoryById($id);
        $oldCategoryName = $oldCategory ? $oldCategory->name : 'Tidak Dikenal';

        // Asumsi updateCategory mengembalikan boolean atau instance kategori yang diupdate
        $updated = $this->categoryService->updateCategory($id, $data);

        // Catat aktivitas: Mengubah kategori
        if ($updated) { // Pastikan update berhasil
            $updatedCategory = $this->categoryService->getCategoryById($id); // Ambil kategori setelah diupdate untuk nama terbaru
            $updatedCategoryName = $updatedCategory ? $updatedCategory->name : 'Tidak Dikenal';

            $this->userActivityService->logActivity(
                Auth::user()->name . ' mengubah kategori: ' . $oldCategoryName . ' menjadi ' . $updatedCategoryName,
                $id, // entity_id
                'Kategori', // entity
                $updatedCategoryName // entity_name
            );
        }

        return redirect()->route('category')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function hapus($id)
    {
        // Ambil kategori sebelum dihapus untuk mendapatkan namanya
        $categoryToDelete = $this->categoryService->getCategoryById($id);
        $categoryName = $categoryToDelete ? $categoryToDelete->name : 'Tidak Dikenal';

        $deleted = $this->categoryService->deleteCategory($id);

        // Catat aktivitas: Menghapus kategori
        if ($deleted) { // Pastikan penghapusan berhasil
            $this->userActivityService->logActivity(
                Auth::user()->name . ' menghapus kategori: ' . $categoryName,
                $id, // entity_id
                'Kategori', // entity
                $categoryName // entity_name
            );
        }

        return redirect()->route('category')->with('success', 'Kategori berhasil dihapus!');
    }
}
