<?php

namespace App\Http\Controllers;

use App\Models\HistoriaArchivo;
use App\Models\HistoriaClinica;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HistoriasController extends Controller
{
    public function index(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();

        $query = HistoriaClinica::where('psicologa_id', $psicologa->id)
            ->with('paciente');

        if ($request->filled('paciente_id')) {
            $query->where('paciente_id', $request->paciente_id);
        }

        $historias = $query->orderBy('fecha_sesion', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $pacientes = Paciente::where('psicologa_id', $psicologa->id)
            ->orderBy('nombre')
            ->get();

        return view('dashboard.historias.index', compact('historias', 'pacientes'));
    }

    public function crear(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $pacientes = Paciente::where('psicologa_id', $psicologa->id)
            ->orderBy('nombre')
            ->get();

        $pacienteId = $request->get('paciente_id');

        return view('dashboard.historias.form', compact('pacientes', 'pacienteId'));
    }

    public function guardar(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();

        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha_sesion' => 'required|date',
            'contenido' => 'required|string',
            'archivos' => 'nullable|array',
            'archivos.*' => 'file|mimes:jpeg,png,jpg,gif,webp,pdf|max:10240',
        ]);

        $historia = HistoriaClinica::create([
            'psicologa_id' => $psicologa->id,
            'paciente_id' => $request->paciente_id,
            'fecha_sesion' => $request->fecha_sesion,
            'contenido' => $request->contenido,
        ]);

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $path = $archivo->store('historias/' . $historia->id, 'public');
                HistoriaArchivo::create([
                    'historia_clinica_id' => $historia->id,
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'archivo_path' => $path,
                    'tipo' => $archivo->getMimeType() === 'application/pdf' ? 'pdf' : 'image',
                ]);
            }
        }

        return redirect()->route('historias.index')
            ->with('success', 'Historia clínica guardada correctamente.');
    }

    public function editar($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $historia = HistoriaClinica::where('psicologa_id', $psicologa->id)
            ->with('archivos')
            ->findOrFail($id);

        $pacientes = Paciente::where('psicologa_id', $psicologa->id)
            ->orderBy('nombre')
            ->get();

        return view('dashboard.historias.form', compact('historia', 'pacientes'));
    }

    public function actualizar(Request $request, $id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $historia = HistoriaClinica::where('psicologa_id', $psicologa->id)
            ->findOrFail($id);

        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha_sesion' => 'required|date',
            'contenido' => 'required|string',
            'archivos' => 'nullable|array',
            'archivos.*' => 'file|mimes:jpeg,png,jpg,gif,webp,pdf|max:10240',
        ]);

        $historia->update($request->only(['paciente_id', 'fecha_sesion', 'contenido']));

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $path = $archivo->store('historias/' . $historia->id, 'public');
                HistoriaArchivo::create([
                    'historia_clinica_id' => $historia->id,
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'archivo_path' => $path,
                    'tipo' => $archivo->getMimeType() === 'application/pdf' ? 'pdf' : 'image',
                ]);
            }
        }

        return redirect()->route('historias.index')
            ->with('success', 'Historia clínica actualizada correctamente.');
    }

    public function eliminar($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $historia = HistoriaClinica::where('psicologa_id', $psicologa->id)
            ->findOrFail($id);

        Storage::disk('public')->deleteDirectory('historias/' . $historia->id);
        $historia->delete();

        return redirect()->route('historias.index')
            ->with('success', 'Historia clínica eliminada.');
    }

    public function eliminarArchivo($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $archivo = HistoriaArchivo::findOrFail($id);

        $historia = $archivo->historiaClinica;
        if ($historia->psicologa_id !== Auth::guard('psicologa')->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($archivo->archivo_path);
        $archivo->delete();

        return back()->with('success', 'Archivo eliminado.');
    }
}
