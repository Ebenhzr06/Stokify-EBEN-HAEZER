<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function signup()
    {
        return view('example.content.auth.signup');
    }

    public function signupSimpan(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ])->validate();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Staff'
        ]);



        return redirect()->route('login')->with('success', 'Akun berhasil didaftarkan!');;
    }

    public function login()
    {
        return view('example.content.auth.login');
    }

    public function loginAksi(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed')
            ]);
        }

        $request->session()->regenerate();

        $user = User::where('email', $request->email)->first(); // Ambil user yang baru dibuat
        $user->last_login_at = now();
        $user->save();

        $user = Auth::user();
        if($user->role === 'Admin')
        {
            return redirect()->route('Admin.index');
        }
        elseif($user->role === 'Manajer gudang')
        {
            return redirect()->route('Manajer.index');
        }
        elseif($user->role === 'Staff')
        { return redirect()->route('Staff.index');
        }

    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        return redirect('/login');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
    ]);

    // Cek apakah email sudah ada di database
    if (User::where('email', $request->email)->exists()) {
        return back()->with('report', 'Email sudah terdaftar, silakan gunakan email lain.')
                     ->withInput();
    }

    // Jika email belum terdaftar, simpan user
    User::create([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    return redirect()->route('users.index')->with('success', 'User berhasil didaftarkan!');
}
}
