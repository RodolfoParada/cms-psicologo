<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();
        return view('dashboard.perfil.index', compact('psicologa'));
    }

    public function actualizar(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:psicologas,email,' . $psicologa->id,
            'telefono' => 'required|string|max:20',
            'password_actual' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['nombre', 'apellidos', 'email', 'telefono']);

        if ($request->filled('password')) {
            if (!Hash::check($request->password_actual, $psicologa->password)) {
                return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
            }
            $data['password'] = $request->password;
        }

        if ($request->hasFile('avatar')) {
            if ($psicologa->foto) {
                Storage::disk('public')->delete($psicologa->foto);
            }
            $data['foto'] = $request->file('avatar')->store('psicologas', 'public');
        }

        $psicologa->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
