<?php
namespace App\Repositories;

use App\Models\UserActivity;

class UserActivityRepository implements UserActivityRepositoryInterface
{
    public function create( $data)
    {
        return UserActivity::create($data);
    }
}
