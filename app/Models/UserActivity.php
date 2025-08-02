<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'entity',
        'entity_name',
        'message',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
