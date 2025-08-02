<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Imports\ProductImport;
use Illuminate\Validation\Rule;
use App\Services\ProductServiceInterface;
use App\Services\UserActivityService; // Tambahkan ini: Import UserActivityService
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductAtribut;
use Illuminate\Support\Facades\Auth; // Tambahkan ini: Untuk mendapatkan Auth::user()

class ProductController extends Controller
{
    protected $productService;
    protected $userActivityService; // Deklarasikan properti untuk UserActivityService

    // Modifikasi konstruktor untuk meng-inject UserActivityService
    public function __construct(ProductServiceInterface $productService, UserActivityService $userActivityService)
    {
        $this->productService = $productService;
        $this->userActivityService = $userActivityService;
    }

    public function products()
    {

        $products = $this->productService->getAllProducts();
        // Tidak mencatat aktivitas di sini sesuai pola sebelumnya
        if (Auth::user()->role == 'Admin') {
            return view('example.content.admin.produk.index', compact('products'));
        } elseif (Auth::user()->role == 'Manajer gudang') {
            return view('example.content.manajer.produk.index', compact('products'));
        }
    }

    public function tambah()
    {
        $categorys = Category::all();
        $suppliers = Supplier::all();
        // Tidak mencatat aktivitas di sini sesuai pola sebelumnya
        return view('example.content.admin.produk.form', compact('categorys', 'suppliers'));
    }

    public function simpan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'supplier_id' => 'required',
            'sku' => 'required|unique:products',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'minimum_stock' => 'required|integer',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);

        // Asumsi createProduct mengembalikan instance produk yang baru dibuat
        $product = $this->productService->createProduct($validated, $request->file('image'));

        // Catat aktivitas: Menambahkan produk baru
        if ($product) { // Pastikan produk berhasil dibuat
            $this->userActivityService->logActivity(
                Auth::user()->name . ' menambahkan produk baru: ' . $product->name,
                $product->id, // entity_id
                'Produk', // entity
                $product->name // entity_name
            );
        }

        return redirect()->route('Admin.product.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = $this->productService->getProductById($id);
        $categorys = Category::all();
        $suppliers = Supplier::all();
        // Tidak mencatat aktivitas di sini sesuai pola sebelumnya
        return view('example.content.admin.produk.form', compact('product', 'categorys', 'suppliers'));
    }

    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'supplier_id' => 'required',
            'sku' => [
                'required',
                Rule::unique('products')->ignore($id)
            ],
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'minimum_stock' => 'required|integer',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);

        // Ambil produk sebelum diupdate untuk mendapatkan nama lamanya
        $oldProduct = $this->productService->getProductById($id);
        $oldProductName = $oldProduct ? $oldProduct->name : 'Tidak Dikenal';

        // Asumsi updateProduct mengembalikan boolean atau instance produk yang diupdate
        $updated = $this->productService->updateProduct($id, $validated, $request->file('image'));

        // Catat aktivitas: Memperbarui produk
        if ($updated) { // Pastikan update berhasil
            $updatedProduct = $this->productService->getProductById($id); // Ambil produk setelah diupdate untuk nama terbaru
            $updatedProductName = $updatedProduct ? $updatedProduct->name : 'Tidak Dikenal';

            $this->userActivityService->logActivity(
                Auth::user()->name . ' memperbarui produk: ' . $oldProductName . ' menjadi ' . $updatedProductName,
                $id, // entity_id
                'Produk', // entity
                $updatedProductName // entity_name
            );
        }

        return redirect()->route('Admin.product.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function hapus($id)
    {
        // Ambil produk sebelum dihapus untuk mendapatkan namanya
        $productToDelete = $this->productService->getProductById($id);
        $productName = $productToDelete ? $productToDelete->name : 'Tidak Dikenal';

        $deleted = $this->productService->deleteProduct($id);

        // Catat aktivitas: Menghapus produk
        if ($deleted) { // Pastikan penghapusan berhasil
            $this->userActivityService->logActivity(
                Auth::user()->name . ' menghapus produk: ' . $productName,
                $id, // entity_id
                'Produk', // entity
                $productName // entity_name
            );
        }

        return redirect()->route('Admin.product.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function export()
    {
        // Catat aktivitas: Mengekspor produk
        $this->userActivityService->logActivity(
            Auth::user()->name . ' mengekspor data produk',
            null, // entity_id (tidak ada ID produk spesifik)
            'Produk', // entity
            'Ekspor Produk' // entity_name
        );
        return Excel::download(new ProductExport, 'product.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new ProductImport, $request->file('file'));

        // Catat aktivitas: Mengimpor produk
        $this->userActivityService->logActivity(
            Auth::user()->name . ' mengimpor data produk dari file: ' . $request->file('file')->getClientOriginalName(),
            null, // entity_id (tidak ada ID produk spesifik)
            'Produk', // entity
            'Impor Produk' // entity_name
        );

        return redirect()->route('Admin.product.index')->with('success', 'Data produk berhasil diimpor!');
    }

    public function show($id)
    {
        $product = Product::with(['category', 'productattributes', 'supplier'])->find($id);

        if (!$product) {
            return redirect()->route('Manajer.product.index')->with('error', 'Produk tidak ditemukan.');
        }

        return view('example.content.manajer.produk.show', compact('product'));
    }
}
