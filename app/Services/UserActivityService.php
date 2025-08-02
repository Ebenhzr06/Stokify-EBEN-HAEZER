<?php

namespace App\Services;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserActivityService
{
    // Ubah definisi fungsi logActivity untuk menerima 4 parameter
    public function logActivity($message, $entityId = null, $entity = 'Umum', $entityName = 'Tidak Spesifik')
    {
        UserActivity::create([
            'user_id'     => Auth::id(),
            'role'        => Auth::user()->role ?? 'unknown',
            'entity'      => $entity,      // Gunakan parameter $entity
            'entity_id'   => $entityId,
            'entity_name' => $entityName,  // Gunakan parameter $entityName
            'message'     => $message,
            'address'     => Request::ip(),
        ]);
    }

    public function getAll()
    {
        return UserActivity::orderBy('created_at', 'desc')->get();
    }

    // Tambahkan juga fungsi filter jika Anda ingin menggunakannya di controller
    public function getActivitiesByEntity($entityType)
    {
        return UserActivity::where('entity', $entityType)
                           ->orderBy('created_at', 'desc')
                           ->get();
    }
}
