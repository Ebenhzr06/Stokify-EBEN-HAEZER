<?php

namespace App\Observers;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserActivityObserver
{
    public function creating(UserActivity $activity)
    {
        $activity->user_id = $activity->user_id ?? Auth::id();
        $activity->role    = $activity->role ?? Auth::user()?->role ?? 'Unknown';
        $activity->address = $activity->address ?? Request::ip();
    }
}
