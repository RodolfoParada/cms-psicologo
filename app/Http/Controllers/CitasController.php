<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitasController extends Controller
{
    public function index(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $query = Cita::where('psicologa_id', $psicologa->id);

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('busqueda')) {
            $busqueda = $request->busqueda;
            $query->where(function ($q) use ($busqueda) {
                $q->where('paciente_nombre', 'like', "%{$busqueda}%")
                  ->orWhere('paciente_telefono', 'like', "%{$busqueda}%");
            });
        }

        $citas = $query->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->paginate(15);

        return view('dashboard.citas.index', compact('citas'));
    }

    public function crear()
    {
        return view('dashboard.citas.form');
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'paciente_nombre' => 'required|string|max:255',
            'paciente_telefono' => 'required|string|max:20',
            'paciente_email' => 'nullable|email|max:255',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'tipo' => 'required|in:online,presencial',
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
            'motivo' => 'nullable|string',
            'notas' => 'nullable|string',
        ]);

        $psicologa = Auth::guard('psicologa')->user();

        $this->sincronizarPaciente($psicologa->id, $request->paciente_nombre, $request->paciente_telefono, $request->paciente_email);

        Cita::create([
            'psicologa_id' => $psicologa->id,
            'paciente_nombre' => $request->paciente_nombre,
            'paciente_telefono' => $request->paciente_telefono,
            'paciente_email' => $request->paciente_email,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'tipo' => $request->tipo,
            'estado' => $request->estado,
            'motivo' => $request->motivo,
            'notas' => $request->notas,
        ]);

        return redirect()->route('citas.index')->with('success', 'Cita creada correctamente.');
    }

    public function editar($id)
    {
        $cita = Cita::where('psicologa_id', Auth::guard('psicologa')->id())
            ->findOrFail($id);

        return view('dashboard.citas.form', compact('cita'));
    }

    public function actualizar(Request $request, $id)
    {
        $cita = Cita::where('psicologa_id', Auth::guard('psicologa')->id())
            ->findOrFail($id);

        $request->validate([
            'paciente_nombre' => 'required|string|max:255',
            'paciente_telefono' => 'required|string|max:20',
            'paciente_email' => 'nullable|email|max:255',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'tipo' => 'required|in:online,presencial',
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
            'motivo' => 'nullable|string',
            'notas' => 'nullable|string',
        ]);

        $psicologa = Auth::guard('psicologa')->user();

        $this->sincronizarPaciente($psicologa->id, $request->paciente_nombre, $request->paciente_telefono, $request->paciente_email);

        $cita->update([
            'paciente_nombre' => $request->paciente_nombre,
            'paciente_telefono' => $request->paciente_telefono,
            'paciente_email' => $request->paciente_email,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'tipo' => $request->tipo,
            'estado' => $request->estado,
            'motivo' => $request->motivo,
            'notas' => $request->notas,
        ]);

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    public function eliminar($id)
    {
        $cita = Cita::where('psicologa_id', Auth::guard('psicologa')->id())
            ->findOrFail($id);
        $cita->delete();

        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente.');
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate(['estado' => 'required|in:pendiente,confirmada,completada,cancelada']);

        $cita = Cita::where('psicologa_id', Auth::guard('psicologa')->id())
            ->findOrFail($id);
        $cita->update(['estado' => $request->estado]);

        return back()->with('success', 'Estado actualizado a "' . $request->estado . '".');
    }

    private function sincronizarPaciente($psicologaId, $nombre, $telefono, $email)
    {
        $paciente = Paciente::where('psicologa_id', $psicologaId)
            ->where('telefono', $telefono)
            ->first();

        if ($paciente) {
            $paciente->update([
                'nombre' => $nombre,
                'email' => $email ?: $paciente->email,
            ]);
        } else {
            Paciente::create([
                'psicologa_id' => $psicologaId,
                'nombre' => $nombre,
                'telefono' => $telefono,
                'email' => $email,
            ]);
        }
    }
}
