<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login()
    {
        $title = 'Login';

        return view('auth.login', compact('title'));
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard.index');
        }

        return back()->with('alert', [
            'status' => 'error',
            'message' => 'Email atau password yang dimasukkan salah!',
        ]);
    }

    public function register()
    {
        $title = 'Register';

        return view('auth.register', compact('title'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:png,jpg|max:1024',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            User::create($data);

            DB::commit();

            $result = ['status' => 'success', 'message' => 'Berhasil membuat akun!'];
        } catch (\Exception $e) {
            DB::rollback();
            $result = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($result);
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
