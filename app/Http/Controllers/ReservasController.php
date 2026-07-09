<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\ConfiguracionWeb;
use App\Models\DescansoConfig;
use App\Models\Disponibilidad;
use App\Models\FrasePublica;
use App\Models\Paciente;
use App\Models\Psicologa;
use App\Models\RedSocial;
use App\Models\Vacacione;
use App\Models\Tema;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReservasController extends Controller
{
    public function index()
    {
        $psicologa = Psicologa::firstOrFail();
        $tema = Tema::find($psicologa->tema_id);
        $carpeta = $tema?->carpeta ?? 'tema-base';
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        $redes = RedSocial::where('psicologa_id', $psicologa->id)->where('activo', true)->get();
        $frases = FrasePublica::where('psicologa_id', $psicologa->id)->get()->keyBy('clave');

        if (! $config?->mostrar_reservas) {
            abort(404);
        }

        $modoVacaciones = $psicologa->modo_vacaciones ?? false;

        return view("themes.{$carpeta}.reservas", compact(
            'psicologa', 'tema', 'carpeta', 'config', 'redes', 'frases', 'modoVacaciones'
        ));
    }

    public function slots(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date_format:Y-m-d',
            'tipo' => 'required|in:online,presencial',
        ]);

        $psicologa = Psicologa::firstOrFail();
        $fecha = Carbon::parse($request->fecha);
        $tipo = $request->tipo;
        $diaSemana = $fecha->dayOfWeek;

        // Check modo vacaciones
        if ($psicologa->modo_vacaciones) {
            return response()->json(['slots' => [], 'error' => 'La psicóloga está de vacaciones.']);
        }

        // Check periodos de vacaciones
        $enVacaciones = Vacacione::where('psicologa_id', $psicologa->id)
            ->where('fecha_inicio', '<=', $fecha->toDateString())
            ->where('fecha_fin', '>=', $fecha->toDateString())
            ->exists();

        if ($enVacaciones) {
            return response()->json(['slots' => [], 'error' => 'Fecha dentro de un periodo vacacional.']);
        }

        // Get descanso config for this tipo
        $descanso = DescansoConfig::where('psicologa_id', $psicologa->id)
            ->where('tipo', $tipo)
            ->first();

        $duracionSesion = $descanso?->duracion_sesion ?? 50;
        $descansoActivo = $descanso?->descanso_activo ?? false;
        $descansoMinutos = $descanso?->descanso_minutos ?? 0;

        $paso = $duracionSesion + ($descansoActivo ? $descansoMinutos : 0);

        // Get disponibilidad for this day and tipo
        $disponibilidad = Disponibilidad::where('psicologa_id', $psicologa->id)
            ->where('tipo', $tipo)
            ->where('dia_semana', $diaSemana)
            ->orderBy('hora_inicio')
            ->get();

        if ($disponibilidad->isEmpty()) {
            return response()->json(['slots' => [], 'error' => 'No hay disponibilidad para este día.']);
        }

        // Get existing citas for this date and tipo
        $citasExistentes = Cita::where('psicologa_id', $psicologa->id)
            ->where('fecha', $fecha->toDateString())
            ->where('tipo', $tipo)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->get();

        $slots = [];

        foreach ($disponibilidad as $disp) {
            $inicio = Carbon::parse($fecha->toDateString() . ' ' . $disp->hora_inicio);
            $fin = Carbon::parse($fecha->toDateString() . ' ' . $disp->hora_fin);

            while ($inicio->copy()->addMinutes($duracionSesion) <= $fin) {
                $slotFin = $inicio->copy()->addMinutes($duracionSesion);

                // Check if slot conflicts with existing cita
                $conflicto = false;
                foreach ($citasExistentes as $cita) {
                    $citaInicio = Carbon::parse($fecha->toDateString() . ' ' . $cita->hora_inicio);
                    $citaFin = Carbon::parse($fecha->toDateString() . ' ' . $cita->hora_fin);

                    if ($inicio < $citaFin && $slotFin > $citaInicio) {
                        $conflicto = true;
                        break;
                    }
                }

                if (! $conflicto) {
                    $slots[] = [
                        'hora' => $inicio->format('H:i'),
                        'hora_fin' => $slotFin->format('H:i'),
                        'label' => $inicio->format('H:i') . ' - ' . $slotFin->format('H:i'),
                    ];
                }

                $inicio->addMinutes($paso);
            }
        }

        return response()->json(['slots' => $slots]);
    }

    public function calendario(Request $request)
    {
        $request->validate([
            'mes' => 'required|integer|between:1,12',
            'anio' => 'required|integer|between:' . date('Y') . ',' . (date('Y') + 2),
            'tipo' => 'required|in:online,presencial',
        ]);

        $psicologa = Psicologa::firstOrFail();
        $tipo = $request->tipo;
        $anio = (int) $request->anio;
        $mes = (int) $request->mes;

        $inicioMes = Carbon::create($anio, $mes, 1);
        $finMes = $inicioMes->copy()->endOfMonth();

        $hoy = Carbon::today();

        // Vacaciones
        $vacaciones = Vacacione::where('psicologa_id', $psicologa->id)
            ->where('fecha_fin', '>=', $inicioMes->toDateString())
            ->where('fecha_inicio', '<=', $finMes->toDateString())
            ->get();

        $diasVacaciones = [];
        foreach ($vacaciones as $v) {
            $periodo = CarbonPeriod::create($v->fecha_inicio, $v->fecha_fin);
            foreach ($periodo as $f) {
                $diasVacaciones[$f->format('Y-m-d')] = true;
            }
        }

        // Get unique days of week with disponibilidad for this tipo
        $diasDisponibles = Disponibilidad::where('psicologa_id', $psicologa->id)
            ->where('tipo', $tipo)
            ->pluck('dia_semana')
->unique();

        $fechasDisponibles = [];

        for ($d = 1; $d <= $finMes->day; $d++) {
            $fecha = Carbon::create($anio, $mes, $d);
            $dateStr = $fecha->format('Y-m-d');

            if ($fecha->lte($hoy)) continue;
            if ($psicologa->modo_vacaciones) continue;
            if (isset($diasVacaciones[$dateStr])) continue;

            if ($diasDisponibles->contains($fecha->dayOfWeek)) {
                $fechasDisponibles[] = $dateStr;
            }
        }

        return response()->json(['fechas' => $fechasDisponibles]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'motivo' => 'nullable|string|max:1000',
            'fecha' => 'required|date_format:Y-m-d',
            'hora_inicio' => 'required|date_format:H:i',
            'tipo' => 'required|in:online,presencial',
        ]);

        $psicologa = Psicologa::firstOrFail();
        $telefono = preg_replace('/\s+/', '', trim($request->telefono));

        // Find or create paciente
        $paciente = Paciente::where('psicologa_id', $psicologa->id)
            ->where('telefono', $telefono)
            ->first();

        if (! $paciente) {
            $paciente = Paciente::create([
                'psicologa_id' => $psicologa->id,
                'nombre' => $request->nombre,
                'telefono' => $telefono,
                'email' => $request->email,
            ]);
        }

        // Get duracion
        $descanso = DescansoConfig::where('psicologa_id', $psicologa->id)
            ->where('tipo', $request->tipo)
            ->first();
        $duracion = $descanso?->duracion_sesion ?? 50;

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($duracion);

        // Check no double booking
        $conflicto = Cita::where('psicologa_id', $psicologa->id)
            ->where('fecha', $request->fecha)
            ->where('tipo', $request->tipo)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->where(function ($q) use ($request, $horaInicio, $horaFin) {
                $q->whereBetween('hora_inicio', [$horaInicio->format('H:i'), $horaFin->format('H:i')])
                  ->orWhereBetween('hora_fin', [$horaInicio->format('H:i'), $horaFin->format('H:i')])
                  ->orWhere(function ($q2) use ($request, $horaInicio, $horaFin) {
                      $q2->where('hora_inicio', '<=', $horaInicio->format('H:i'))
                         ->where('hora_fin', '>=', $horaFin->format('H:i'));
                  });
            })
            ->exists();

        if ($conflicto) {
            return response()->json(['error' => 'Este horario ya no está disponible. Por favor, recarga la página.'], 409);
        }

        // Create cita
        $cita = Cita::create([
            'psicologa_id' => $psicologa->id,
            'paciente_nombre' => $request->nombre,
            'paciente_telefono' => $telefono,
            'paciente_email' => $request->email,
            'fecha' => $request->fecha,
            'hora_inicio' => $horaInicio->format('H:i'),
            'hora_fin' => $horaFin->format('H:i'),
            'tipo' => $request->tipo,
            'estado' => 'pendiente',
            'motivo' => $request->motivo,
        ]);

        // Send email notification
        $this->enviarNotificacion($psicologa, $cita);

        return response()->json([
            'success' => true,
            'cita' => [
                'fecha' => $request->fecha,
                'hora_inicio' => $horaInicio->format('H:i'),
                'hora_fin' => $horaFin->format('H:i'),
                'tipo' => $request->tipo,
                'nombre' => $request->nombre,
            ],
        ]);
    }

    private function enviarNotificacion($psicologa, $cita)
    {
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();

        if (! $config || ! $config->notificaciones_activas || ! $config->email_notificaciones) {
            return;
        }

        try {
            $host = $config->email_smtp_host;
            $port = $config->email_smtp_port;
            $user = $config->email_smtp_user;
            $pass = $config->email_smtp_pass;
            $encryption = $config->email_smtp_encryption;
            $to = $config->email_notificaciones;

            if (! $host || ! $user || ! $pass) {
                return;
            }

            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.host' => $host,
                'mail.mailers.smtp.port' => $port ?: 587,
                'mail.mailers.smtp.username' => $user,
                'mail.mailers.smtp.password' => $pass,
                'mail.mailers.smtp.encryption' => $encryption ?: 'tls',
                'mail.from.address' => $user,
                'mail.from.name' => $psicologa->nombre_completo,
            ]);

            $tipoLabel = $cita->tipo === 'online' ? 'Online' : 'Presencial';

            Mail::raw(
                "Nueva cita agendada\n\n" .
                "Paciente: {$cita->paciente_nombre}\n" .
                "Teléfono: {$cita->paciente_telefono}\n" .
                "Email: {$cita->paciente_email}\n" .
                "Fecha: {$cita->fecha}\n" .
                "Horario: {$cita->hora_inicio} - {$cita->hora_fin}\n" .
                "Tipo: {$tipoLabel}\n" .
                ($cita->motivo ? "Motivo: {$cita->motivo}\n" : ''),
                function ($message) use ($to, $psicologa) {
                    $message->to($to)
                            ->subject('Nueva cita reservada — ' . $psicologa->nombre_completo);
                }
            );
        } catch (\Exception $e) {
            Log::error('Error al enviar notificación de cita: ' . $e->getMessage());
        }
    }
}
