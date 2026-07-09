<?php

namespace App\Http\Controllers;

use App\Models\BlogArticulo;
use App\Models\Cita;
use App\Models\HistoriasClinica;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuscadorController extends Controller
{
    public function buscar(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $q = trim($request->q);

        if (strlen($q) < 2) {
            return redirect()->route('dashboard.inicio');
        }

        $citas = Cita::where('psicologa_id', $psicologa->id)
            ->where(function ($query) use ($q) {
                $query->where('paciente_nombre', 'like', "%{$q}%")
                    ->orWhere('paciente_telefono', 'like', "%{$q}%")
                    ->orWhere('motivo', 'like', "%{$q}%");
            })
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        $pacientes = Paciente::where('psicologa_id', $psicologa->id)
            ->where(function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('nombre')
            ->limit(10)
            ->get();

        $historias = HistoriasClinica::where('psicologa_id', $psicologa->id)
            ->where(function ($query) use ($q) {
                $query->where('titulo', 'like', "%{$q}%")
                    ->orWhere('contenido', 'like', "%{$q}%");
            })
            ->orderBy('fecha_sesion', 'desc')
            ->limit(10)
            ->get();

        $articulos = BlogArticulo::where('psicologa_id', $psicologa->id)
            ->where(function ($query) use ($q) {
                $query->where('titulo', 'like', "%{$q}%")
                    ->orWhere('contenido', 'like', "%{$q}%")
                    ->orWhere('extracto', 'like', "%{$q}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.buscador.resultados', compact(
            'q', 'citas', 'pacientes', 'historias', 'articulos'
        ));
    }
}
