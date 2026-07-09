<?php

namespace App\Http\Controllers;

use App\Models\DescansoConfig;
use App\Models\Disponibilidad;
use App\Models\Vacacione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisponibilidadController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();

        $descansoOnline = DescansoConfig::firstOrCreate(
            ['psicologa_id' => $psicologa->id, 'tipo' => 'online'],
            ['duracion_sesion' => 50, 'descanso_minutos' => 0]
        );

        $descansoPresencial = DescansoConfig::firstOrCreate(
            ['psicologa_id' => $psicologa->id, 'tipo' => 'presencial'],
            ['duracion_sesion' => 50, 'descanso_minutos' => 0]
        );

        $disponibilidadOnline = Disponibilidad::where('psicologa_id', $psicologa->id)
            ->where('tipo', 'online')
            ->get()
            ->groupBy('dia_semana');

        $disponibilidadPresencial = Disponibilidad::where('psicologa_id', $psicologa->id)
            ->where('tipo', 'presencial')
            ->get()
            ->groupBy('dia_semana');

        $vacaciones = Vacacione::where('psicologa_id', $psicologa->id)
            ->orderBy('fecha_inicio')
            ->get();

        $diasSemana = Disponibilidad::diasSemana();

        return view('dashboard.disponibilidad', compact(
            'descansoOnline', 'descansoPresencial',
            'disponibilidadOnline', 'disponibilidadPresencial',
            'vacaciones', 'diasSemana'
        ));
    }

    public function guardarDescanso(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:online,presencial',
            'duracion_sesion' => 'required|integer|min:15|max:240',
            'descanso_activo' => 'boolean',
            'descanso_minutos' => 'required|integer|min:0|max:60',
        ]);

        $psicologa = Auth::guard('psicologa')->user();

        DescansoConfig::updateOrCreate(
            ['psicologa_id' => $psicologa->id, 'tipo' => $request->tipo],
            [
                'duracion_sesion' => $request->duracion_sesion,
                'descanso_activo' => $request->boolean('descanso_activo'),
                'descanso_minutos' => $request->descanso_minutos,
            ]
        );

        return back()->with('success', 'Configuración de ' . $request->tipo . ' guardada correctamente.');
    }

    public function guardarHorarios(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:online,presencial',
            'horarios' => 'required|array',
            'horarios.*.dia' => 'required|integer|between:0,6',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => 'required|date_format:H:i',
        ]);

        $psicologa = Auth::guard('psicologa')->user();

        Disponibilidad::where('psicologa_id', $psicologa->id)
            ->where('tipo', $request->tipo)
            ->delete();

        foreach ($request->horarios as $horario) {
            Disponibilidad::create([
                'psicologa_id' => $psicologa->id,
                'tipo' => $request->tipo,
                'dia_semana' => $horario['dia'],
                'hora_inicio' => $horario['hora_inicio'],
                'hora_fin' => $horario['hora_fin'],
            ]);
        }

        return back()->with('success', 'Horario de ' . $request->tipo . ' guardado correctamente.');
    }

    public function toggleModoVacaciones(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $psicologa->update(['modo_vacaciones' => $request->boolean('activo')]);

        return back()->with('success', 'Modo vacaciones ' . ($request->boolean('activo') ? 'activado' : 'desactivado') . '.');
    }

    public function guardarVacaciones(Request $request)
    {
        $request->validate([
            'periodos' => 'required|array',
            'periodos.*.fecha_inicio' => 'required|date',
            'periodos.*.fecha_fin' => 'required|date|after_or_equal:periodos.*.fecha_inicio',
        ]);

        $psicologa = Auth::guard('psicologa')->user();

        foreach ($request->periodos as $periodo) {
            Vacacione::create([
                'psicologa_id' => $psicologa->id,
                'fecha_inicio' => $periodo['fecha_inicio'],
                'fecha_fin' => $periodo['fecha_fin'],
            ]);
        }

        return back()->with('success', 'Periodo(s) de vacaciones añadido(s).');
    }

    public function eliminarVacaciones($id)
    {
        $vacacion = Vacacione::where('psicologa_id', Auth::guard('psicologa')->id())
            ->findOrFail($id);
        $vacacion->delete();

        return back()->with('success', 'Periodo de vacaciones eliminado.');
    }
}
