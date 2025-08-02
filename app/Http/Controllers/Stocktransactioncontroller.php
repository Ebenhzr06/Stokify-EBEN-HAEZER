<?php

namespace App\Http\Controllers;

use App\Services\StockTransactionServiceInterface;
use App\Models\StockTransaction; // Pastikan ini di-import
use App\Models\Product; // Import model Product
use App\Models\User;    // Import model User
use App\Services\UserActivityService; // Tambahkan ini: Import UserActivityService
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang sedang login
use Illuminate\Support\Facades\DB;


class StockTransactionController extends Controller
{
    protected $stocktransactionService;
    protected $userActivityService; // Deklarasikan properti untuk UserActivityService

    // Modifikasi konstruktor untuk meng-inject UserActivityService
    public function __construct(StockTransactionServiceInterface $stocktransactionService, UserActivityService $userActivityService)
    {
        $this->stocktransactionService = $stocktransactionService;
        $this->userActivityService = $userActivityService;
    }

    public function stocks()
    {
        $stocks = $this->stocktransactionService->getAllProductAndUser();
        if (Auth::user()->role == 'Admin') {
            return view('example.content.admin.stock.index', compact('stocks'));
        } elseif (Auth::user()->role == 'Manajer gudang') {
            return view('example.content.manajer.stock.index', compact('stocks'));
        }
    }

    /**
     * Menampilkan form untuk menambah transaksi stok baru.
     */
    public function tambah()
    {
        $products = Product::all();
        $users = User::all();

        if (Auth::user()->role == 'Admin') {
            return view('example.content.admin.stock.form', compact('products', 'users'));
        } elseif (Auth::user()->role == 'Manajer gudang') {
            return view('example.content.manajer.stock.form', compact('products', 'users'));
        }
    }

    /**
     * Menyimpan transaksi stok baru ke database.
     */
    public function simpan(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            // 'transaction_no' => 'required|string|unique:stock_transactions,transaction_no', // Jika Anda menambahkan kolom ini di migrasi dan model
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id', // Atau bisa Auth::id() jika user otomatis diambil dari yang login
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'date' => 'nullable|date', // Sesuai dengan kolom 'date' di model Anda
            'status' => 'nullable|in:Pending,Diterima,Ditolak,Dikeluarkan',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Siapkan data untuk disimpan
        $data = [
            // 'transaction_no' => $request->transaction_no, // Jika Anda menambahkan kolom ini
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'status' => $request->status,
            'notes' => $request->notes,
        ];

        $stockTransaction = StockTransaction::create($data);

        // Catat aktivitas: Menambah transaksi stok baru
        if ($stockTransaction) {
            $productName = Product::find($stockTransaction->product_id)->name ?? 'Produk Tidak Dikenal';
            $userName = User::find($stockTransaction->user_id)->name ?? 'User Tidak Dikenal';
            $this->userActivityService->logActivity(
                Auth::user()->name . ' menambahkan transaksi stok ' . $stockTransaction->type . ' sejumlah ' . $stockTransaction->quantity . ' untuk produk "' . $productName . '" oleh "' . $userName . '"',
                $stockTransaction->id, // entity_id
                'Transaksi Stok', // entity
                'Transaksi ' . $stockTransaction->type . ' Produk ' . $productName // entity_name
            );
        }

        if (Auth::user()->role == 'Admin') {
            return redirect()->route('Admin.stock.index')->with('success', 'Transaksi stok berhasil ditambahkan!');
        } elseif (Auth::user()->role == 'Manajer gudang') {
            return redirect()->route('Manajer.stock.index')->with('success', 'Transaksi stok berhasil ditambahkan!');
        }
    }

    /**
     * Menampilkan form untuk mengedit transaksi stok.
     */
    public function edit($id)
    {
        $stock = StockTransaction::with(['product', 'user'])->find($id); // Eager load untuk form
        if (!$stock) {
            return redirect()->route('Admin.stock.index')->with('error', 'Transaksi stok tidak ditemukan.');
        }

        $products = Product::all();
        $users = User::all();

        // Catat aktivitas: Mengakses form edit transaksi stok
        $productName = $stock->product->name ?? 'Produk Tidak Dikenal';
        $userName = $stock->user->name ?? 'User Tidak Dikenal';

        if (Auth::user()->role == 'Admin') {
            return view('example.content.admin.stock.form', compact('stock', 'products', 'users'));
        } elseif (Auth::user()->role == 'Manajer gudang') {
            return view('example.content.manajer.stock.form', compact('stock', 'products', 'users'));
        }
    }

    /**
     * Memperbarui transaksi stok di database.
     */
    public function update($id, Request $request)
    {
        $stock = StockTransaction::find($id);
        if (!$stock) {
            return redirect()->route('Admin.stock.index')->with('error', 'Transaksi stok tidak ditemukan.');
        }

        // Ambil data lama untuk logging
        $oldType = $stock->type;
        $oldQuantity = $stock->quantity;
        $oldProductId = $stock->product_id;
        $oldUserId = $stock->user_id;

        // Validasi data yang masuk (sesuaikan dengan simpan)
        $request->validate([
            // 'transaction_no' => 'required|string|unique:stock_transactions,transaction_no,' . $id, // Jika ada kolom ini
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'status' => 'nullable|in:Pending,Diterima,Ditolak,Dikeluarkan',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Siapkan data untuk update
        $data = [
            // 'transaction_no' => $request->transaction_no, // Jika ada kolom ini
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'status' => $request->status,
            'notes' => $request->notes,
        ];

        $stock->update($data); // Memperbarui data transaksi

        // Catat aktivitas: Memperbarui transaksi stok
        $oldProductName = Product::find($oldProductId)->name ?? 'Produk Lama Tidak Dikenal';
        $oldUserName = User::find($oldUserId)->name ?? 'User Lama Tidak Dikenal';
        $newProductName = Product::find($stock->product_id)->name ?? 'Produk Baru Tidak Dikenal';
        $newUserName = User::find($stock->user_id)->name ?? 'User Baru Tidak Dikenal';

        $message = Auth::user()->name . ' memperbarui transaksi stok ID: ' . $id . '. Dari (Tipe: ' . $oldType . ', Qty: ' . $oldQuantity . ', Produk: ' . $oldProductName . ', User: ' . $oldUserName . ') menjadi (Tipe: ' . $stock->type . ', Qty: ' . $stock->quantity . ', Produk: ' . $newProductName . ', User: ' . $newUserName . ')';
        $this->userActivityService->logActivity(
            $message,
            $id, // entity_id
            'Transaksi Stok', // entity
            'Update Transaksi Stok ID: ' . $id // entity_name
        );

        if (Auth::user()->role == 'Admin') {
            return redirect()->route('Admin.stock.index')->with('success', 'Transaksi stok berhasil diperbarui!');
        } elseif (Auth::user()->role == 'Manajer gudang') {
            return redirect()->route('Manajer.stock.index')->with('success', 'Transaksi stok berhasil diperbarui!');
        }
    }

    /**
     * Menghapus transaksi stok dari database.
     */
    public function hapus($id)
    {
        $stock = StockTransaction::find($id);
        if (!$stock) {
            return redirect()->route('Admin.stock.index')->with('error', 'Transaksi stok tidak ditemukan.');
        }

        // Ambil detail sebelum dihapus untuk logging
        $productName = $stock->product->name ?? 'Produk Tidak Dikenal';
        $userName = $stock->user->name ?? 'User Tidak Dikenal';
        $type = $stock->type;
        $quantity = $stock->quantity;

        $stock->delete();

        // Catat aktivitas: Menghapus transaksi stok
        $this->userActivityService->logActivity(
            Auth::user()->name . ' menghapus transaksi stok ID: ' . $id . ' (Tipe: ' . $type . ', Qty: ' . $quantity . ', Produk: ' . $productName . ', User: ' . $userName . ')',
            $id, // entity_id
            'Transaksi Stok', // entity
            'Hapus Transaksi Stok ID: ' . $id // entity_name
        );


         if (Auth::user()->role == 'Admin') {
           return redirect()->route('Admin.stock.index')->with('success', 'Transaksi stok berhasil dihapus!');
        } elseif (Auth::user()->role == 'Manajer gudang') {
            return redirect()->route('Manajer.stock.index')->with('success', 'Transaksi stok berhasil dihapus!');
        }
    }

       public function confirm(StockTransaction $stockTransaction)
    {
        // Pastikan transaksi ini terkait dengan produk yang statusnya masih 'Pending'
        if ($stockTransaction->status !== 'Pending') {
            return back()->with('error', 'Transaksi ini tidak bisa dikonfirmasi.');
        }

        DB::transaction(function () use ($stockTransaction) {
            $product = $stockTransaction->product;

            // Perbarui status transaksi menjadi 'Diterima'
            $stockTransaction->status = 'Diterima';
            $stockTransaction->save();

            // Lakukan update stok produk berdasarkan tipe transaksi
            // Jika tipe 'Masuk', stok bertambah. Jika 'Keluar', stok berkurang.
            if ($stockTransaction->type === 'Masuk') {
                $product->stock += $stockTransaction->quantity;
            } elseif ($stockTransaction->type === 'Keluar') {
                $product->stock -= $stockTransaction->quantity;
            }
            $product->save();
        });

        // Setelah konfirmasi, angka di dashboard akan berkurang karena status transaksi sudah bukan 'Pending'
        return redirect()->route('Staff.stock.index')->with('success', 'Transaksi berhasil dikonfirmasi.');
    }

    /**
     * Menolak transaksi stok dan memperbarui status produk menjadi 'Ditolak'.
     */
    public function reject(StockTransaction $stockTransaction)
    {
        // Pastikan transaksi terkait dengan produk yang statusnya masih 'Pending'
        if ($stockTransaction->status !== 'Pending') {
            return back()->with('error', 'Transaksi ini tidak bisa ditolak.');
        }

        $stockTransaction->status = 'Ditolak';
        $stockTransaction->save();

        return redirect()->route('Staff.stock.index')->with('success', 'Transaksi berhasil ditolak.');
    }

    /**
     * Mengeluarkan transaksi stok yang sudah dikonfirmasi.
     * Mengembalikan stok ke keadaan sebelum transaksi dikonfirmasi.
     */
    public function remove(StockTransaction $stockTransaction)
    {
        // Pastikan transaksi terkait dengan produk yang statusnya sudah 'Diterima'
        if ($stockTransaction->status !== 'Diterima') {
            return back()->with('error', 'Transaksi ini tidak bisa dikeluarkan.');
        }

        DB::transaction(function () use ($stockTransaction) {
            $product = $stockTransaction->product;

            // Perbarui status transaksi menjadi 'Dikeluarkan'
            $stockTransaction->status = 'Dikeluarkan';
            $stockTransaction->save();

            // Kembalikan stok ke keadaan semula
            if ($stockTransaction->type === 'Masuk') {
                $product->stock -= $stockTransaction->quantity;
            } elseif ($stockTransaction->type === 'Keluar') {
                $product->stock += $stockTransaction->quantity;
            }
            $product->save();
        });

        return redirect()->route('Staff.stock.index')->with('success', 'Transaksi berhasil dikeluarkan.');
    }
}
