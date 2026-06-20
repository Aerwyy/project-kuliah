<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('user')) {
            return $this->redirectByRole(session('user')['role']);
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        session([
            'user' => [
                'id'    => $user->id_user,
                'nama'  => $user->nama,
                'email' => $user->email,
                'role'  => $user->role,
                'foto'  => $user->foto_profil,
            ]
        ]);

        return $this->redirectByRole($user->role);
    }

    public function logout()
    {
        session()->forget('user');
        return redirect('/');
    }

    public function showRegister()
    {
        if (session('user')) {
            return $this->redirectByRole(session('user')['role']);
        }
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'murid',
        ]);

        session([
            'user' => [
                'id'    => $user->id_user,
                'nama'  => $user->nama,
                'email' => $user->email,
                'role'  => $user->role,
                'foto'  => $user->foto_profil,
            ]
        ]);

        return redirect()->route('student');
    }

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin'  => redirect()->route('admin'),
            'tutor'  => redirect()->route('student'),
            default  => redirect()->route('student'),
        };
    }
}
