<?php

namespace App\Http\Controllers;

use App\Models\Psicologa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('psicologa')->check()) {
            return redirect('/panel-psicologa');
        }

        return view('auth.login-psicologa');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'telefono' => 'required|string',
            'password' => 'required|string',
        ]);

        $psicologa = Psicologa::where('email', $request->email)
            ->where('telefono', $request->telefono)
            ->first();

        if (!$psicologa || !Hash::check($request->password, $psicologa->password)) {
            return back()->withErrors([
                'error' => 'Los datos introducidos no coinciden con nuestros registros.',
            ])->withInput();
        }

        $remember = $request->boolean('remember');

        Auth::guard('psicologa')->login($psicologa, $remember);

        $request->session()->regenerate();

        return redirect()->intended('/panel-psicologa');
    }

    public function logout(Request $request)
    {
        Auth::guard('psicologa')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/acceso-psicologa');
    }
}
