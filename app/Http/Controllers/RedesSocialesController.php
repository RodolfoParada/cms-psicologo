<?php

namespace App\Http\Controllers;

use App\Models\RedSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedesSocialesController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();
        $plataformas = RedSocial::plataformas();
        $redes = RedSocial::where('psicologa_id', $psicologa->id)
            ->get()
            ->keyBy('plataforma');

        return view('dashboard.redes-sociales.index', compact('plataformas', 'redes'));
    }

    public function guardar(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $plataformas = RedSocial::plataformas();

        foreach ($plataformas as $clave => $info) {
            $url = $request->input("url_{$clave}");
            $activo = $request->boolean("activo_{$clave}");

            if ($url) {
                RedSocial::updateOrCreate(
                    ['psicologa_id' => $psicologa->id, 'plataforma' => $clave],
                    ['url' => $url, 'activo' => $activo]
                );
            } else {
                RedSocial::where('psicologa_id', $psicologa->id)
                    ->where('plataforma', $clave)
                    ->delete();
            }
        }

        return back()->with('success', 'Redes sociales actualizadas.');
    }
}
