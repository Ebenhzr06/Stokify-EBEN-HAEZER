<?php

namespace App\Http\Controllers;

use App\Models\User; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Illuminate\Validation\Rule; // Untuk validasi unik email saat update
use App\Services\UserServiceInterface;
use App\Services\UserActivityService; // Tambahkan ini: Import UserActivityService
use Illuminate\Support\Facades\Auth; // Tambahkan ini: Untuk mendapatkan Auth::user()

class UserController extends Controller
{
    protected $userService;
    protected $userActivityService; // Deklarasikan properti untuk UserActivityService

    public function __construct(UserServiceInterface $userService, UserActivityService $userActivityService)
    {
        $this->userService = $userService;
        $this->userActivityService = $userActivityService;
    }

    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        $users = $this->userService->getAll();



        return view('example.content.admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk menambah pengguna baru.
     */
    public function create()
    {
        // Catat aktivitas: Mengakses form tambah pengguna

        return view('example.content.admin.users.form'); // Sesuaikan path view jika berbeda
    }

    /**
     * Menyimpan pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Email harus unik
            'password' => 'required|string|min:8|confirmed', // Password minimal 8 karakter dan konfirmasi
            'role' => 'required|string|in:admin,manajer gudang,staff', // Contoh role yang valid
        ]);

        $user = User::create([ // Menggunakan mass assignment sesuai $fillable di User model
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // WAJIB: Hash password
            'role' => $request->role,
        ]);

        // Catat aktivitas: Menambahkan pengguna baru
        if ($user) {
            $this->userActivityService->logActivity(
                Auth::user()->name . ' menambahkan pengguna baru: ' . $user->name . ' (Role: ' . $user->role . ')',
                $user->id, // entity_id
                'Pengguna', // entity
                $user->name // entity_name
            );
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     */
    public function edit(User $user) // Menggunakan Route Model Binding
    {
        // Catat aktivitas: Mengakses form edit pengguna


        return view('example.content.admin.users.form', compact('user')); // Sesuaikan path view jika berbeda
    }

    /**
     * Memperbarui pengguna di database.
     */
    public function update(Request $request, User $user) // Menggunakan Route Model Binding
    {
        // Ambil data lama untuk logging
        $oldName = $user->name;
        $oldEmail = $user->email;
        $oldRole = $user->role;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Email harus unik kecuali untuk user yang sedang diedit
            ],
            // Password tidak wajib diisi jika tidak ingin diubah
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,manajer gudang,staff,raja',
        ]);

        $data = $request->only(['name', 'email', 'role']);

        // Hanya perbarui password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password); // WAJIB: Hash password
        }

        $user->update($data); // Memperbarui data user

        // Catat aktivitas: Memperbarui pengguna
        $message = Auth::user()->name .'Memperbaruhi Pengguna Dari (Nama: ' . $oldName . ', Email: ' . $oldEmail . ', Role: ' . $oldRole . ') menjadi (Nama: ' . $user->name . ', Email: ' . $user->email . ', Role: ' . $user->role . ')';
        $this->userActivityService->logActivity(
            $message,
            $user->id, // entity_id
            'Pengguna', // entity
            $user->name // entity_name
        );

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * Menghapus pengguna dari database.
     */
    public function destroy(User $user) // Menggunakan Route Model Binding
    {
        // Ambil detail sebelum dihapus untuk logging
        $userName = $user->name;
        $userEmail = $user->email;
        $userRole = $user->role;
        $userId = $user->id; // Simpan ID sebelum dihapus

        $user->delete();

        // Catat aktivitas: Menghapus pengguna
        $this->userActivityService->logActivity(
            Auth::user()->name . ' menghapus pengguna: ' . $userName . ' (Email: ' . $userEmail . ', Role: ' . $userRole . ')',
            $userId, // entity_id
            'Pengguna', // entity
            $userName // entity_name
        );

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
