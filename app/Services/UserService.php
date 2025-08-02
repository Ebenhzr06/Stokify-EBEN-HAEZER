<?php

namespace App\Services;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Repositories\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll()
    {
        return $this->userRepository->all();
    }

    public function getById($id)
    {
        return $this->userRepository->find($id);
    }

    public function create($data)
    {
        $user = $this->userRepository->create($data);

        $this->logActivity('Menambahkan user', $user->id);

        return $user;
    }

    public function update($id, $data)
    {
        $this->logActivity('Mengubah user', $id);

        return $this->userRepository->update($id, $data);
    }

    public function delete($id)
    {
        $this->logActivity('Menghapus user', $id);

        return $this->userRepository->delete($id);
    }

    protected function logActivity($message, $entityId)
    {
        UserActivity::create([
            'user_id'     => Auth::id(),
            'role'        => Auth::user()->role ?? 'unknown',
            'entity'      => 'user',
            'entity_id'   => $entityId,
            'entity_name' => 'User',
            'message'     => $message,
            'address'     => Request::ip(),
        ]);
    }
}
