<?php

namespace App\Http\Controllers;

use App\Services\StockOpnameService;
use App\Services\UserActivityService; // Pastikan ini diimpor
use App\Models\UserActivity; // Tidak wajib di sini jika hanya digunakan di Service
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log; // Hanya jika Anda membutuhkannya untuk debugging

class LaporanController extends Controller
{
    protected $stockOpnameService, $userActivityService;

    public function __construct(
        StockOpnameService $stockOpnameService,
        UserActivityService $userActivityService)
    {
        $this->stockOpnameService = $stockOpnameService;
        $this->userActivityService = $userActivityService;
    }

    public function stok()
    {
        $laporan = $this->stockOpnameService->getAll();

        if (Auth::user()->role == 'Admin') {
           return view('example.content.admin.report.laporanstok', [
            'stocks' => $laporan,
        ]);
        } elseif (Auth::user()->role == 'Manajer gudang') {
          return view('example.content.manajer.report.laporanstok', [
            'stocks' => $laporan,
        ]);
        } elseif (Auth::user()->role == 'Staff') {
          return view('example.content.staff.stok.stok', [
            'stocks' => $laporan,
        ]);
        }
    }

    public function transaksi()
    {
        $laporan = $this->stockOpnameService->getAll();

         if (Auth::user()->role == 'Admin') {
           return view('example.content.admin.report.laporantransaksi', [
            'stocks' => $laporan,
        ]);
        } elseif (Auth::user()->role == 'Manajer gudang') {
          return view('example.content.manajer.report.laporantransaksi', [
            'stocks' => $laporan,
        ]);
        }
    }

    public function user()
    {
        // Ambil semua aktivitas
        $activities = $this->userActivityService->getAll();

        // Tampilkan ke view
        return view('example.content.admin.report.laporanuser', compact('activities'));
    }
}
