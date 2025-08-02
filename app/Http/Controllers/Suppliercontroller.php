<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Services\UserActivityService; // Tambahkan ini: Import UserActivityService
use Illuminate\Support\Facades\Auth; // Tambahkan ini: Untuk mendapatkan Auth::user()

class SupplierController extends Controller
{
    protected $userActivityService; // Deklarasikan properti untuk UserActivityService

    // Modifikasi konstruktor untuk meng-inject UserActivityService
    public function __construct(UserActivityService $userActivityService)
    {
        $this->userActivityService = $userActivityService;
    }

    public function suppliers()
    {
        $supplier = Supplier::all();
         if (Auth::user()->role == 'Admin') {
            return view('example.content.admin.supplier.index', compact('supplier'));
        } elseif (Auth::user()->role == 'Manajer gudang') {
           return view('example.content.manajer.supplier.index', compact('supplier'));
        }

    }

    public function tambah()
    {
        // Tidak mencatat aktivitas di sini sesuai permintaan
        return view('example.content.admin.supplier.form');
    }

    public function simpan(Request $request)
    {
        // Anda mungkin ingin menambahkan validasi di sini
        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        ];

        $supplier = Supplier::create($data); // Simpan hasil pembuatan supplier

        // Catat aktivitas: Membuat supplier baru
        if ($supplier) { // Pastikan supplier berhasil dibuat
            $this->userActivityService->logActivity(
                Auth::user()->name . ' menambahkan supplier baru: ' . $supplier->name,
                $supplier->id, // entity_id dari supplier yang baru dibuat
                'Supplier', // entity
                $supplier->name // entity_name
            );
        }

        return redirect()->route('Admin.supplier.index')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);

        // Tidak mencatat aktivitas di sini sesuai permintaan
        return view('example.content.admin.supplier.form', ['supplier' => $supplier]);
    }

    public function update($id, Request $request)
    {
        // Ambil supplier sebelum diupdate untuk mendapatkan nama lamanya
        $oldSupplier = Supplier::find($id);
        $oldSupplierName = $oldSupplier ? $oldSupplier->name : 'Tidak Dikenal';

        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        ];

        $updated = Supplier::find($id)->update($data);

        // Catat aktivitas: Memperbarui supplier
        if ($updated) { // Pastikan supplier berhasil diupdate
            $updatedSupplier = Supplier::find($id); // Ambil lagi untuk nama terbaru
            $updatedSupplierName = $updatedSupplier ? $updatedSupplier->name : 'Tidak Dikenal';

            $this->userActivityService->logActivity(
                Auth::user()->name . ' memperbarui supplier: ' . $oldSupplierName . ' menjadi ' . $updatedSupplierName,
                $id, // entity_id dari supplier yang diperbarui
                'Supplier', // entity
                $updatedSupplierName // entity_name
            );
        }

        return redirect()->route('Admin.supplier.index')->with('success', 'Supplier berhasil diperbarui!');
    }

    public function hapus($id)
    {
        // Ambil supplier sebelum dihapus untuk mendapatkan namanya
        $supplierToDelete = Supplier::find($id);
        $supplierName = $supplierToDelete ? $supplierToDelete->name : 'Tidak Dikenal';

        $deleted = Supplier::find($id)->delete();

        // Catat aktivitas: Menghapus supplier
        if ($deleted) { // Pastikan supplier berhasil dihapus
            $this->userActivityService->logActivity(
                Auth::user()->name . ' menghapus supplier: ' . $supplierName,
                $id, // entity_id dari supplier yang dihapus
                'Supplier', // entity
                $supplierName // entity_name
            );
        }

        return redirect()->route('Admin.supplier.index')->with('success', 'Supplier berhasil dihapus!');
    }
}
