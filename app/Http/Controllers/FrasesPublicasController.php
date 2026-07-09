<?php

namespace App\Http\Controllers;

use App\Models\FrasePublica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrasesPublicasController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();
        $slots = FrasePublica::slots();
        $frases = FrasePublica::where('psicologa_id', $psicologa->id)
            ->get()
            ->keyBy('clave');

        return view('dashboard.frases-publicas.index', compact('slots', 'frases'));
    }

    public function guardar(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $slots = FrasePublica::slots();

        foreach ($slots as $clave => $slot) {
            if ($request->has($clave)) {
                FrasePublica::updateOrCreate(
                    ['psicologa_id' => $psicologa->id, 'clave' => $clave],
                    [
                        'valor' => $request->input($clave),
                        'defecto' => $slot['defecto'],
                    ]
                );
            }
        }

        return back()->with('success', 'Frases actualizadas correctamente.');
    }

    public function restablecer($clave)
    {
        $psicologa = Auth::guard('psicologa')->user();
        FrasePublica::where('psicologa_id', $psicologa->id)
            ->where('clave', $clave)
            ->delete();

        return back()->with('success', 'Frase restablecida al valor por defecto.');
    }
}
