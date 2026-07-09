<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\DescansoConfig;
use App\Models\Disponibilidad;
use App\Models\Vacacione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CalendarioController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();

        $citas = Cita::where('psicologa_id', $psicologa->id)
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get()
            ->map(function ($cita) {
                return [
                    'id'        => $cita->id,
                    'title'     => $cita->paciente_nombre . ' (' . ucfirst($cita->tipo) . ')',
                    'start'     => $cita->hora_inicio,
                    'end'       => $cita->hora_fin,
                    'date'      => $cita->fecha->format('Y-m-d'),
                    'color'     => $this->colorEstado($cita->estado),
                    'estado'    => $cita->estado,
                    'tipo'      => $cita->tipo,
                    'motivo'    => $cita->motivo,
                    'telefono'  => $cita->paciente_telefono,
                    'email'     => $cita->paciente_email,
                ];
            });

        $descansoOnline = DescansoConfig::where('psicologa_id', $psicologa->id)
            ->where('tipo', 'online')->first();
        $descansoPresencial = DescansoConfig::where('psicologa_id', $psicologa->id)
            ->where('tipo', 'presencial')->first();

        $minMaxOnline = Disponibilidad::where('psicologa_id', $psicologa->id)
            ->where('tipo', 'online')
            ->selectRaw('MIN(hora_inicio) as min_hora, MAX(hora_fin) as max_hora')
            ->first();
        $minMaxPresencial = Disponibilidad::where('psicologa_id', $psicologa->id)
            ->where('tipo', 'presencial')
            ->selectRaw('MIN(hora_inicio) as min_hora, MAX(hora_fin) as max_hora')
            ->first();

        $vacaciones = Vacacione::where('psicologa_id', $psicologa->id)
            ->where('fecha_fin', '>=', now()->subDay())
            ->get()
            ->map(function ($v) {
                return [
                    'inicio' => $v->fecha_inicio->format('Y-m-d'),
                    'fin'    => $v->fecha_fin->format('Y-m-d'),
                ];
            });

        $modoVacaciones = $psicologa->modo_vacaciones;

        return view('dashboard.calendario.index', compact(
            'citas', 'descansoOnline', 'descansoPresencial',
            'minMaxOnline', 'minMaxPresencial', 'vacaciones', 'modoVacaciones'
        ));
    }

    public function store(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();

        $validator = Validator::make($request->all(), [
            'paciente_nombre'   => 'required|string|max:255',
            'paciente_telefono' => 'required|string|max:20',
            'paciente_email'    => 'nullable|email|max:255',
            'fecha'             => 'required|date',
            'hora_inicio'       => 'required|date_format:H:i',
            'hora_fin'          => 'required|date_format:H:i|after:hora_inicio',
            'tipo'              => 'required|in:online,presencial',
            'motivo'            => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cita = Cita::create([
            'psicologa_id'      => $psicologa->id,
            'paciente_nombre'   => $request->paciente_nombre,
            'paciente_telefono' => $request->paciente_telefono,
            'paciente_email'    => $request->paciente_email,
            'fecha'             => $request->fecha,
            'hora_inicio'       => $request->hora_inicio,
            'hora_fin'          => $request->hora_fin,
            'tipo'              => $request->tipo,
            'estado'            => 'pendiente',
            'motivo'            => $request->motivo,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cita creada correctamente.',
            'cita'    => [
                'id'       => $cita->id,
                'title'    => $cita->paciente_nombre . ' (' . ucfirst($cita->tipo) . ')',
                'start'    => $cita->hora_inicio,
                'end'      => $cita->hora_fin,
                'date'     => $cita->fecha->format('Y-m-d'),
                'color'    => $this->colorEstado($cita->estado),
                'estado'   => $cita->estado,
                'tipo'     => $cita->tipo,
                'motivo'   => $cita->motivo,
                'telefono' => $cita->paciente_telefono,
                'email'    => $cita->paciente_email,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $cita = Cita::where('psicologa_id', $psicologa->id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'paciente_nombre'   => 'required|string|max:255',
            'paciente_telefono' => 'required|string|max:20',
            'paciente_email'    => 'nullable|email|max:255',
            'fecha'             => 'required|date',
            'hora_inicio'       => 'required|date_format:H:i',
            'hora_fin'          => 'required|date_format:H:i|after:hora_inicio',
            'tipo'              => 'required|in:online,presencial',
            'estado'            => 'required|in:pendiente,confirmada,completada,cancelada',
            'motivo'            => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cita->update($request->only([
            'paciente_nombre', 'paciente_telefono', 'paciente_email',
            'fecha', 'hora_inicio', 'hora_fin', 'tipo', 'estado', 'motivo',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Cita actualizada correctamente.',
            'cita'    => [
                'id'       => $cita->id,
                'title'    => $cita->paciente_nombre . ' (' . ucfirst($cita->tipo) . ')',
                'start'    => $cita->hora_inicio,
                'end'      => $cita->hora_fin,
                'date'     => $cita->fecha->format('Y-m-d'),
                'color'    => $this->colorEstado($cita->estado),
                'estado'   => $cita->estado,
                'tipo'     => $cita->tipo,
                'motivo'   => $cita->motivo,
                'telefono' => $cita->paciente_telefono,
                'email'    => $cita->paciente_email,
            ],
        ]);
    }

    public function destroy($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $cita = Cita::where('psicologa_id', $psicologa->id)->findOrFail($id);
        $cita->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cita eliminada correctamente.',
        ]);
    }

    private function colorEstado($estado): string
    {
        return match ($estado) {
            'pendiente'   => '#f59e0b',
            'confirmada'  => '#10b981',
            'completada'  => '#3b82f6',
            'cancelada'   => '#ef4444',
            default       => '#6b7280',
        };
    }
}
