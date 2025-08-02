<?php

namespace App\Services;

use App\Models\StockTransaction;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Repositories\StockTransactionRepositoryInterface;
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal

class StockTransactionService implements StockTransactionServiceInterface
{
    protected $stockTransactionRepository;

    public function __construct(StockTransactionRepositoryInterface $stockTransactionRepository)
    {
        $this->stockTransactionRepository = $stockTransactionRepository;
    }

    public function getAll()
    {
        return $this->stockTransactionRepository->getAll();
    }

    public function findById($id)
    {
        return $this->stockTransactionRepository->findById($id);
    }

    public function create($data)
    {
        $transaction = $this->stockTransactionRepository->create($data);

        $this->logActivity('Menambahkan transaksi stok', $transaction->id);

        return $transaction;
    }

    public function update($id, $data)
    {
        $this->logActivity('Mengubah transaksi stok', $id);

        return $this->stockTransactionRepository->update($id, $data);
    }

    public function delete($id)
    {
        $this->logActivity('Menghapus transaksi stok', $id);

        return $this->stockTransactionRepository->delete($id);
    }

    public function getAllProductAndUser()
    {
        return $this->stockTransactionRepository->allwithProductAndUser();
    }

    protected function logActivity($message, $entityId)
    {
        UserActivity::create([
            'user_id'     => Auth::id(),
            'role'        => Auth::user()->role ?? 'unknown',
            'entity'      => 'stock_transaction',
            'entity_id'   => $entityId,
            'entity_name' => 'Stock Transaction',
            'message'     => $message,
            'address'     => Request::ip(),
        ]);
    }

    /**
     * Menghitung total produk masuk (type 'Masuk').
     *
     * @return int
     */
    public function getTotalMasukThisMonth(): int
    {
        return StockTransaction::where('type', 'Masuk')
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->sum('quantity');
    }

    /**
     * Menghitung total produk keluar (type 'Keluar').
     *
     * @return int
     */
    public function getTotalKeluarThisMonth(): int
    {
        return StockTransaction::where('type', 'Keluar')
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->sum('quantity');
    }

    public function getTotalMasukLastMonth(): int
    {
        return StockTransaction::where('type', 'Masuk')
            ->whereYear('date', Carbon::now()->subMonth()->year)
            ->whereMonth('date', Carbon::now()->subMonth()->month)
            ->sum('quantity');
    }

    public function getTotalKeluarLastMonth(): int
    {
        return StockTransaction::where('type', 'Keluar')
            ->whereYear('date', Carbon::now()->subMonth()->year)
            ->whereMonth('date', Carbon::now()->subMonth()->month)
            ->sum('quantity');
    }

    /**
     * Mengambil data produk masuk dan keluar per bulan untuk beberapa bulan terakhir.
     * (Pertahankan method ini jika masih digunakan di bagian lain aplikasi Anda)
     *
     * @param int $months Jumlah bulan terakhir yang ingin diambil datanya.
     * @return array Contoh: ['labels' => ['Jan 2025', 'Feb 2025'], 'incoming' => [100, 120], 'outgoing' => [50, 60]]
     */
    public function getMonthlyStockMovementData(int $months = 6): array
    {
        $labels = [];
        $incomingData = [];
        $outgoingData = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $labels[] = $date->format('M Y'); // Contoh: Jul 2025

            $incomingCount = StockTransaction::where('type', 'Masuk') // Menggunakan 'Masuk' sesuai dengan method di atas
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('quantity');
            $incomingData[] = $incomingCount;

            $outgoingCount = StockTransaction::where('type', 'Keluar') // Menggunakan 'Keluar' sesuai dengan method di atas
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('quantity');
            $outgoingData[] = $outgoingCount;
        }

        return [
            'labels' => $labels,
            'incoming' => $incomingData,
            'outgoing' => $outgoingData,
        ];
    }

    /**
     * Mengambil data produk masuk dan keluar per hari untuk beberapa hari terakhir.
     *
     * @param int $days Jumlah hari terakhir yang ingin diambil datanya.
     * @return array Contoh: ['labels' => ['Sen, 01 Jul', 'Sel, 02 Jul'], 'incoming' => [10, 12], 'outgoing' => [5, 6]]
     */
    public function getDailyStockMovementData(int $days = 7): array
    {
        $labels = [];
        $incomingData = [];
        $outgoingData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i); // Gunakan Carbon::today() untuk konsistensi
            $startOfDay = $date->copy()->startOfDay();
            $endOfDay = $date->copy()->endOfDay();

            // Format tanggal agar lebih user-friendly di sumbu X
            // 'D' untuk nama hari singkat (Sen, Sel), 'd M' untuk tanggal dan bulan (01 Jul)
            $labels[] = $date->format('D, d M');

            $incomingCount = StockTransaction::where('type', 'Masuk')
                ->whereBetween('date', [$startOfDay, $endOfDay])
                ->sum('quantity');
            $incomingData[] = (float) $incomingCount; // Pastikan float

            $outgoingCount = StockTransaction::where('type', 'Keluar')
                ->whereBetween('date', [$startOfDay, $endOfDay])
                ->sum('quantity');
            $outgoingData[] = (float) $outgoingCount; // Pastikan float
        }

        return [
            'labels' => $labels,
            'incoming' => $incomingData,
            'outgoing' => $outgoingData,
        ];
    }

    public function getAllWithProduct()
    {
        return StockTransaction::with('product')->get();
    }
}
