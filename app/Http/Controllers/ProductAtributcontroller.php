<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductAtribut;
use App\Services\UserActivityService; // Tambahkan ini: Import UserActivityService
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini: Untuk mendapatkan Auth::user()

class ProductAtributController extends Controller
{
    protected $userActivityService; // Deklarasikan properti untuk UserActivityService

    // Modifikasi konstruktor untuk meng-inject UserActivityService
    public function __construct(UserActivityService $userActivityService)
    {
        $this->userActivityService = $userActivityService;
    }

    public function productatributs()
    {
        $productatributs = ProductAtribut::all();

        // Tidak mencatat aktivitas di sini sesuai pola sebelumnya
        return view('example.content.admin.atribut.index', compact('productatributs'));
    }

    public function tambah()
    {
        $products = Product::all();
        // Tidak mencatat aktivitas di sini sesuai pola sebelumnya
        return view('example.content.admin.atribut.form', compact('products'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required',
            'value' => 'required',
        ]);

        $data = $request->only(['product_id', 'name', 'value']);

        $productAtribut = ProductAtribut::create($data); // Simpan hasil pembuatan atribut

        // Catat aktivitas: Menambahkan atribut produk baru
        if ($productAtribut) { // Pastikan atribut berhasil dibuat
            $productName = Product::find($productAtribut->product_id)->name ?? 'Produk Tidak Dikenal';
            $this->userActivityService->logActivity(
                Auth::user()->name . ' menambahkan atribut "' . $productAtribut->name . ': ' . $productAtribut->value . '" untuk produk: ' . $productName,
                $productAtribut->id, // entity_id
                'Atribut Produk', // entity
                $productAtribut->name . ' (' . $productAtribut->value . ')' // entity_name
            );
        }

        return redirect()->route('Admin.atribut.index')->with('success', 'Atribut produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $productatributs = ProductAtribut::find($id);
        $products = Product::all();

        // Tidak mencatat aktivitas di sini sesuai pola sebelumnya
        return view('example.content.admin.atribut.form', ['productatribut' => $productatributs], compact('products'));
    }

    public function update($id, Request $request)
    {
        // Ambil atribut sebelum diupdate untuk mendapatkan nama lamanya
        $oldProductAtribut = ProductAtribut::find($id);
        $oldAttributeName = $oldProductAtribut ? $oldProductAtribut->name . ': ' . $oldProductAtribut->value : 'Atribut Tidak Dikenal';
        $oldProductName = $oldProductAtribut ? (Product::find($oldProductAtribut->product_id)->name ?? 'Produk Tidak Dikenal') : 'Produk Tidak Dikenal';


        $data = [
            'name' => $request->name,
            'value' => $request->value,
        ];

        $updated = ProductAtribut::find($id)->update($data);

        // Catat aktivitas: Memperbarui atribut produk
        if ($updated) { // Pastikan update berhasil
            $updatedProductAtribut = ProductAtribut::find($id); // Ambil atribut setelah diupdate untuk nama terbaru
            $updatedAttributeName = $updatedProductAtribut ? $updatedProductAtribut->name . ': ' . $updatedProductAtribut->value : 'Tidak Dikenal';
            $updatedProductName = $updatedProductAtribut ? (Product::find($updatedProductAtribut->product_id)->name ?? 'Produk Tidak Dikenal') : 'Produk Tidak Dikenal';

            $this->userActivityService->logActivity(
                Auth::user()->name . ' memperbarui atribut produk "' . $oldAttributeName . '" menjadi "' . $updatedAttributeName . '" untuk produk: ' . $updatedProductName,
                $id, // entity_id
                'Atribut Produk', // entity
                $updatedAttributeName // entity_name
            );
        }

        return redirect()->route('Admin.atribut.index')->with('success', 'Atribut produk berhasil diperbarui!');
    }

    public function hapus($id)
    {
        // Ambil atribut sebelum dihapus untuk mendapatkan namanya
        $productAtributToDelete = ProductAtribut::find($id);
        $attributeName = $productAtributToDelete ? $productAtributToDelete->name . ': ' . $productAtributToDelete->value : 'Atribut Tidak Dikenal';
        $productName = $productAtributToDelete ? (Product::find($productAtributToDelete->product_id)->name ?? 'Produk Tidak Dikenal') : 'Produk Tidak Dikenal';


        $deleted = ProductAtribut::find($id)->delete();

        // Catat aktivitas: Menghapus atribut produk
        if ($deleted) { // Pastikan penghapusan berhasil
            $this->userActivityService->logActivity(
                Auth::user()->name . ' menghapus atribut "' . $attributeName . '" untuk produk: ' . $productName,
                $id, // entity_id
                'Atribut Produk', // entity
                $attributeName // entity_name
            );
        }

        return redirect()->route('Admin.atribut.index')->with('success', 'Atribut produk berhasil dihapus!');
    }
}
