<?php

namespace App\Services;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserActivityService
{
    // Fungsi untuk mencatat aktivitas
    public function logActivity($message, $entityId) // Pastikan ini public
    {
        UserActivity::create([
            'user_id'     => Auth::id(),
            'role'        => Auth::user()->role ?? 'unknown',
            'entity'      => 'Laporan', // Anda bisa sesuaikan ini jika perlu
            'entity_id'   => $entityId,
            'entity_name' => 'Akses Laporan', // Anda bisa sesuaikan ini jika perlu
            'message'     => $message,
            'address'     => Request::ip(),
        ]);
    }

    // Contoh method lain untuk mengambil semua aktivitas
    public function getAll()
    {
        return UserActivity::orderBy('created_at', 'desc')->get();
    }
}
