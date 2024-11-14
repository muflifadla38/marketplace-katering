<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\SiteTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use SiteTrait;

    public function login()
    {
        $title = 'Login';

        return view('auth.login', compact('title'));
    }

    public function authenticate(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $message = 'Email atau password salah!';
            if (auth()->attempt($credentials)) {
                $request->session()->regenerate();

                $message = 'Berhasil login!';
            }

            $result = ['status' => 'success', 'message' => $message];
        } catch (\Exception $e) {
            $result = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($result);
    }

    public function register($role)
    {
        $title = 'Register';

        return view('auth.register', compact('title', 'role'));
    }

    public function store($role, Request $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                'phone' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $data['role_id'] = $role == 'merchant' ? 1 : 2;
            $data['image'] = $this->storeFile($request->image, 'users');

            User::create($data);

            DB::commit();

            $result = ['status' => 'success', 'message' => "Berhasil membuat akun $role! Silahkan login untuk melanjutkan!"];
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

        return redirect()->route('auth.login');
    }
}
