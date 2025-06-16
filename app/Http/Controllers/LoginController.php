<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index', [
            'title' => 'login',
            'active' => 'login'
        ]); //masuk ke login model dash
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required|max:255|alpha',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }        

        return back()->with('loginError', 'Login failed!');
        dd('berhasil');

    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        // request()->session()->flush();  // Menghapus semua data sesi
        request()->session()->regenerateToken();

        // dd('Logout successful!'); // Debugging here to check proce

        return redirect('/login');
    }

}
