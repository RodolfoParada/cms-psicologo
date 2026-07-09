<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PacientesController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();

        $pacientes = Paciente::where('psicologa_id', $psicologa->id)
            ->withCount('citas')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.pacientes.index', compact('pacientes'));
    }

    public function crear()
    {
        return view('dashboard.pacientes.form');
    }

    public function guardar(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();

        $request->validate([
            'nombre' => 'required|string|max:200',
            'telefono' => 'required|string|max:20|unique:pacientes,telefono,NULL,id,psicologa_id,' . $psicologa->id,
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        Paciente::create([
            'psicologa_id' => $psicologa->id,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente creado correctamente.');
    }

    public function editar($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $paciente = Paciente::where('psicologa_id', $psicologa->id)
            ->findOrFail($id);

        return view('dashboard.pacientes.form', compact('paciente'));
    }

    public function actualizar(Request $request, $id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $paciente = Paciente::where('psicologa_id', $psicologa->id)
            ->findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:200',
            'telefono' => 'required|string|max:20|unique:pacientes,telefono,' . $id . ',id,psicologa_id,' . $psicologa->id,
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $paciente->update($request->only([
            'nombre', 'telefono', 'email', 'direccion', 'fecha_nacimiento', 'observaciones',
        ]));

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente actualizado correctamente.');
    }

    public function eliminar($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $paciente = Paciente::where('psicologa_id', $psicologa->id)
            ->findOrFail($id);

        Cita::where('psicologa_id', $psicologa->id)
            ->where('paciente_telefono', $paciente->telefono)
            ->update(['paciente_telefono' => null]);

        $paciente->delete();

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente eliminado.');
    }

    public function show($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $paciente = Paciente::where('psicologa_id', $psicologa->id)
            ->findOrFail($id);

        $citas = Cita::where('psicologa_id', $psicologa->id)
            ->where('paciente_telefono', $paciente->telefono)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->paginate(10);

        return view('dashboard.pacientes.show', compact('paciente', 'citas'));
    }

    public function buscar(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $q = $request->get('q', '');

        $pacientes = Paciente::where('psicologa_id', $psicologa->id)
            ->where(function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get(['id', 'nombre', 'telefono', 'email']);

        return response()->json($pacientes);
    }
}
