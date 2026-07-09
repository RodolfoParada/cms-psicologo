<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionWeb;
use App\Models\Especialidad;
use App\Models\Faq;
use App\Models\Precio;
use App\Models\Servicio;
use App\Models\Tema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemasController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();
        $temas = Tema::where('activo', true)->get();

        return view('dashboard.temas.index', compact('temas', 'psicologa'));
    }

    public function seleccionar(Request $request)
    {
        $request->validate([
            'tema_id' => 'required|exists:temas,id',
        ]);

        $psicologa = Auth::guard('psicologa')->user();
        $psicologa->update(['tema_id' => $request->tema_id]);

        return redirect()->route('temas.index')
            ->with('success', 'Tema visual activado correctamente.');
    }

    public function previsualizar($id)
    {
        $tema = Tema::findOrFail($id);

        $psicologa = Auth::guard('psicologa')->user();
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        $servicios = Servicio::where('psicologa_id', $psicologa->id)->orderBy('orden')->get();
        $especialidades = Especialidad::where('psicologa_id', $psicologa->id)->orderBy('orden')->get();
        $preciosOnline = Precio::where('psicologa_id', $psicologa->id)->where('tipo', 'online')->orderBy('orden')->get();
        $preciosPresencial = Precio::where('psicologa_id', $psicologa->id)->where('tipo', 'presencial')->orderBy('orden')->get();
        $faqs = Faq::where('psicologa_id', $psicologa->id)->where('activo', true)->orderBy('orden')->get();

        return view("themes.{$tema->carpeta}.index", compact(
            'tema', 'psicologa', 'config',
            'servicios', 'especialidades',
            'preciosOnline', 'preciosPresencial',
            'faqs'
        ));
    }
}
