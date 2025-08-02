<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\StockTransaction;
use App\Services\ProductService;
use App\Services\StockTransactionService;
use App\Services\UserService;
use App\Services\UserActivityService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $productService, $stockTransactionService, $userService, $userActivityService;

    public function __construct(
        ProductService $productService,
        StockTransactionService $stockTransactionService,
        UserService $userService,
        UserActivityService $userActivityService
    ) {
        $this->productService = $productService;
        $this->stockTransactionService = $stockTransactionService;
        $this->userService = $userService;
        $this->userActivityService = $userActivityService;
    }

    public function index(Request $request)
    {

        //dashboard admin
        $totalProduk = $this->productService->getTotalProducts();
        $startOfThisMonth = Carbon::now()->startOfMonth();
        $totalProdukLastMonth = $this->productService->getTotalProductsBeforeDate($startOfThisMonth);
        $percentageProduk = 0;
        if ($totalProdukLastMonth > 0) {
            $percentageProduk = (($totalProduk - $totalProdukLastMonth) / $totalProdukLastMonth) * 100;
        }

        $totalStockMasuk = $this->stockTransactionService->getTotalMasukThisMonth();
        $totalStockKeluar = $this->stockTransactionService->getTotalKeluarThisMonth();
        $totalStockMasukLastMonth = $this->stockTransactionService->getTotalMasukLastMonth();
        $totalStockKeluarLastMonth = $this->stockTransactionService->getTotalKeluarLastMonth();

        $percentageMasuk = 0;
        if ($totalStockMasukLastMonth > 0) {
            $percentageMasuk = (($totalStockMasuk - $totalStockMasukLastMonth) / $totalStockMasukLastMonth) * 100;
        }

        $percentageKeluar = 0;
        if ($totalStockKeluarLastMonth > 0) {
            $percentageKeluar = (($totalStockKeluar - $totalStockKeluarLastMonth) / $totalStockKeluarLastMonth) * 100;
        }

        $timeframe = $request->query('timeframe', 'day');
        $periodToShow = ($timeframe === 'month') ? 6 : 7;

        $stockMovementData = [];
        $timeframeText = '';
        $startDate = null;
        $endDate = Carbon::now()->endOfDay();

        if ($timeframe === 'month') {
            $stockMovementData = $this->stockTransactionService->getMonthlyStockMovementData($periodToShow);
            $timeframeText = 'This Year';
            $startDate = Carbon::now()->subMonths($periodToShow - 1)->startOfMonth();
        } else {
            $stockMovementData = $this->stockTransactionService->getDailyStockMovementData($periodToShow);
            $timeframeText = 'This Week';
            $startDate = Carbon::today()->subDays($periodToShow - 1)->startOfDay();
        }

        $revenueDataRaw = StockTransaction::selectRaw(
            ($timeframe === 'month' ? 'DATE_FORMAT(date, "%Y-%m")' : 'DATE(date)') . ' as date,
            SUM((products.selling_price - products.purchase_price) * stock_transactions.quantity) as total'
        )
            ->join('products', 'stock_transactions.product_id', '=', 'products.id')
            ->where('stock_transactions.type', 'Keluar')
            ->whereBetween('stock_transactions.date', [$startDate, $endDate])
            ->groupByRaw(($timeframe === 'month' ? 'DATE_FORMAT(date, "%Y-%m")' : 'DATE(date)'))
            ->orderBy('date')
            ->get();

        $revenueValues = [];
        for ($i = $periodToShow - 1; $i >= 0; $i--) {
            $date = ($timeframe === 'month') ?
                Carbon::now()->subMonths($i)->format('Y-m') :
                Carbon::today()->subDays($i)->format('Y-m-d');
            $foundRevenue = $revenueDataRaw->firstWhere('date', $date);
            $revenueValues[] = $foundRevenue ? (float) $foundRevenue->total : 0.0;
        }
        $stockMovementData['revenue'] = $revenueValues;

        $currentPeriodGrossProfit = StockTransaction::join('products', 'stock_transactions.product_id', '=', 'products.id')
            ->where('stock_transactions.type', 'Keluar')
            ->whereBetween('stock_transactions.date', [$startDate, $endDate])
            ->sum(DB::raw('(products.selling_price - products.purchase_price) * stock_transactions.quantity'));

        $prevStartDate = ($timeframe === 'month') ? Carbon::now()->subMonths($periodToShow * 2 - 1)->startOfMonth() : Carbon::today()->subDays($periodToShow * 2 - 1)->startOfDay();
        $prevEndDate = ($timeframe === 'month') ? Carbon::now()->subMonths($periodToShow)->endOfMonth() : Carbon::today()->subDays($periodToShow)->endOfDay();

        $previousPeriodGrossProfit = StockTransaction::join('products', 'stock_transactions.product_id', '=', 'products.id')
            ->where('stock_transactions.type', 'Keluar')
            ->whereBetween('stock_transactions.date', [$prevStartDate, $prevEndDate])
            ->sum(DB::raw('(products.selling_price - products.purchase_price) * stock_transactions.quantity'));

        $percentageChange = 0;
        if ($previousPeriodGrossProfit > 0) {
            $percentageChange = (($currentPeriodGrossProfit - $previousPeriodGrossProfit) / $previousPeriodGrossProfit) * 100;
        }

        $percentageProduk = 0;
        if ($totalProdukLastMonth > 0) {
            $percentageProduk = (($totalProduk - $totalProdukLastMonth) / $totalProdukLastMonth) * 100;
        } elseif ($totalProduk > 0 && $totalProdukLastMonth == 0) {
            // Jika ada produk bulan ini tapi tidak ada produk bulan lalu
            $percentageProduk = 'Baru'; // Atau teks lain yang Anda inginkan
        }

        $activities = $this->userActivityService->getAll();
        // Mendapatkan bulan ini dan bulan lalu
        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // 1. Perhitungan Jumlah Produk
        $totalProduk = Product::count();
        $totalProdukLastMonth = Product::whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])->count();

        $percentageProduk = 0;
        if ($totalProdukLastMonth > 0) {
            $percentageProduk = (($totalProduk - $totalProdukLastMonth) / $totalProdukLastMonth) * 100;
        } elseif ($totalProduk > 0 && $totalProdukLastMonth == 0) {
            $percentageProduk = 'Baru';
        }

        // 2. Perbaikan untuk Jumlah Transaksi Keluar
        $totalTransaksiKeluar = StockTransaction::where('type', 'keluar')->count();
        $totalTransaksiKeluarLastMonth = StockTransaction::where('type', 'keluar')
            ->whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])
            ->count();

        $percentageTransaksiKeluar = 0;
        if ($totalTransaksiKeluarLastMonth > 0) {
            $percentageTransaksiKeluar = (($totalTransaksiKeluar - $totalTransaksiKeluarLastMonth) / $totalTransaksiKeluarLastMonth) * 100;
        } elseif ($totalTransaksiKeluar > 0 && $totalTransaksiKeluarLastMonth == 0) {
            $percentageTransaksiKeluar = 'Baru';
        }

        // 3. Perbaikan untuk Jumlah Transaksi Masuk
        $totalTransaksiMasuk = StockTransaction::where('type', 'masuk')->count();
        $totalTransaksiMasukLastMonth = StockTransaction::where('type', 'masuk')
            ->whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])
            ->count();

        $percentageTransaksiMasuk = 0;
        if ($totalTransaksiMasukLastMonth > 0) {
            $percentageTransaksiMasuk = (($totalTransaksiMasuk - $totalTransaksiMasukLastMonth) / $totalTransaksiMasukLastMonth) * 100;
        } elseif ($totalTransaksiMasuk > 0 && $totalTransaksiMasukLastMonth == 0) {
            $percentageTransaksiMasuk = 'Baru';
        }


        //dashboard manajer
        $stokMenipis = Product::where('minimum_stock', '>', function ($query) {
            $query->selectRaw('SUM(CASE WHEN type = "masuk" THEN quantity ELSE -quantity END)')
                ->from('stock_transactions')
                ->whereColumn('stock_transactions.product_id', 'products.id');
        })->get();

        $today = Carbon::today();
        $barangMasukHariIni = StockTransaction::where('type', 'masuk')
            ->whereDate('created_at', $today)
            ->get();
        $barangKeluarHariIni = StockTransaction::where('type', 'keluar')
            ->whereDate('created_at', $today)
            ->get();

        //dashboard staff
         $productsInCount = StockTransaction::where('type', 'Masuk')
            ->where('status', 'Pending') // Perbaikan di sini: cek status di model StockTransaction
            ->count();

        $productsOutCount = StockTransaction::where('type', 'Keluar')
            ->where('status', 'Pending') // Perbaikan di sini: cek status di model StockTransaction
            ->count();



        $user = Auth::user();
        if ($user->role === 'Admin') {
            return view('example.content.admin.dashboard.index', compact(
                'activities',
                'totalProduk',
                'totalStockMasuk',
                'totalStockKeluar',
                'stockMovementData',
                'percentageMasuk',
                'percentageKeluar',
                'percentageProduk',
                'currentPeriodGrossProfit', // Mengubah nama variabel dari $totalGrossProfit
                'timeframeText',
                'percentageChange'
            ));
        } elseif ($user->role === 'Manajer gudang') {
            return view('example.content.manajer.dashboard.index', compact(
                'stokMenipis',
                'barangMasukHariIni',
                'barangKeluarHariIni'
            ));
        } elseif ($user->role === 'Staff')
            return view('example.content.staff.dashboard.index', compact(
                'productsInCount',
                'productsOutCount'
            ));


        // return view('example.content.admin.dashboard.index', compact(
        //     'activities',
        //     'totalProduk',
        //     'totalStockMasuk',
        //     'totalStockKeluar',
        //     'stockMovementData',
        //     'percentageMasuk',
        //     'percentageKeluar',
        //     'percentageProduk',
        //     'currentPeriodGrossProfit', // Mengubah nama variabel dari $totalGrossProfit
        //     'timeframeText',
        //     'percentageChange'
        // ));
    }
}
