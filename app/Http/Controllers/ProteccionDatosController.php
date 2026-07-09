<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionWeb;
use App\Models\Paciente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProteccionDatosController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();

        $config = ConfiguracionWeb::firstOrCreate(
            ['psicologa_id' => $psicologa->id],
            ['plantilla_proteccion_datos' => $this->plantillaDefault($psicologa)]
        );

        return view('dashboard.proteccion-datos.index', compact('config', 'psicologa'));
    }

    public function guardar(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();

        $request->validate([
            'plantilla' => 'nullable|string',
        ]);

        ConfiguracionWeb::updateOrCreate(
            ['psicologa_id' => $psicologa->id],
            ['plantilla_proteccion_datos' => $request->plantilla]
        );

        return back()->with('success', 'Plantilla de protección de datos guardada correctamente.');
    }

    public function descargarPlantilla()
    {
        $psicologa = Auth::guard('psicologa')->user();
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();

        $contenido = $config?->plantilla_proteccion_datos ?: $this->plantillaDefault($psicologa);

        $html = view('dashboard.proteccion-datos.pdf', [
            'contenido' => $contenido,
            'psicologa' => $psicologa,
            'paciente' => null,
        ])->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4');

        return $pdf->download('plantilla-proteccion-datos.pdf');
    }

    public function descargarPaciente($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $paciente = Paciente::where('psicologa_id', $psicologa->id)->findOrFail($id);

        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        $contenido = $config?->plantilla_proteccion_datos ?: $this->plantillaDefault($psicologa);

        $contenido = $this->rellenarDatos($contenido, $psicologa, $paciente);

        $html = view('dashboard.proteccion-datos.pdf', [
            'contenido' => $contenido,
            'psicologa' => $psicologa,
            'paciente' => $paciente,
        ])->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4');

        $nombreArchivo = 'proteccion-datos-' . Str::slug($paciente->nombre) . '.pdf';
        return $pdf->download($nombreArchivo);
    }

    private function rellenarDatos($contenido, $psicologa, $paciente)
    {
        $reemplazos = [
            '[NOMBRE_PACIENTE]'   => $paciente->nombre,
            '[TELEFONO_PACIENTE]' => $paciente->telefono,
            '[EMAIL_PACIENTE]'    => $paciente->email ?? '_________________',
            '[DIRECCION_PACIENTE]' => $paciente->direccion ?? '_________________',
            '[FECHA_ACTUAL]'      => now()->format('d/m/Y'),
            '[NOMBRE_PSICOLOGA]'  => $psicologa->nombre_completo,
            '[NUMERO_COLEGIADO]'  => $psicologa->numero_colegiado ?? '_________________',
            '[EMAIL_PSICOLOGA]'   => $psicologa->email,
            '[TELEFONO_PSICOLOGA]' => $psicologa->telefono,
        ];

        return str_replace(array_keys($reemplazos), array_values($reemplazos), $contenido);
    }

    private function plantillaDefault($psicologa)
    {
        return '<h2 style="text-align:center;">DOCUMENTO DE INFORMACIÓN Y CONSENTIMIENTO<br>PROTECCIÓN DE DATOS PERSONALES</h2>

<p style="text-align:right;"><strong>Fecha:</strong> [FECHA_ACTUAL]</p>

<hr>

<h3>1. RESPONSABLE DEL TRATAMIENTO</h3>

<p><strong>Nombre:</strong> [NOMBRE_PSICOLOGA]</p>
<p><strong>Nº Colegiado/a:</strong> [NUMERO_COLEGIADO]</p>
<p><strong>Email:</strong> [EMAIL_PSICOLOGA]</p>
<p><strong>Teléfono:</strong> [TELEFONO_PSICOLOGA]</p>

<hr>

<h3>2. DATOS DE LA PERSONA PACIENTE</h3>

<p><strong>Nombre y apellidos:</strong> [NOMBRE_PACIENTE]</p>
<p><strong>Teléfono:</strong> [TELEFONO_PACIENTE]</p>
<p><strong>Email:</strong> [EMAIL_PACIENTE]</p>
<p><strong>Dirección:</strong> [DIRECCION_PACIENTE]</p>

<hr>

<h3>3. FINALIDAD DEL TRATAMIENTO</h3>

<p>Los datos personales facilitados serán tratados con la finalidad de gestionar la relación terapéutica, incluyendo:</p>
<ul>
    <li>La prestación de servicios de psicología y salud mental.</li>
    <li>La gestión de citas y seguimiento de las sesiones.</li>
    <li>La elaboración y mantenimiento de la historia clínica.</li>
    <li>La comunicación de información relacionada con el proceso terapéutico.</li>
</ul>

<h3>4. LEGITIMACIÓN</h3>

<p>El tratamiento de sus datos se realiza en base al consentimiento expreso de la persona interesada, así como para la ejecución de un contrato de prestación de servicios sanitarios.</p>

<h3>5. PLAZO DE CONSERVACIÓN</h3>

<p>Los datos serán conservados durante el tiempo necesario para cumplir con la finalidad para la que se recabaron y durante los plazos legalmente establecidos (mínimo 5 años tras la finalización del tratamiento, según la legislación sanitaria aplicable).</p>

<h3>6. DERECHOS</h3>

<p>La persona paciente tiene derecho a:</p>
<ul>
    <li>Acceder a sus datos personales.</li>
    <li>Solicitar la rectificación de los datos inexactos.</li>
    <li>Solicitar la supresión de los datos cuando ya no sean necesarios.</li>
    <li>Solicitar la limitación del tratamiento.</li>
    <li>Oponerse al tratamiento.</li>
    <li>Solicitar la portabilidad de los datos.</li>
</ul>

<p>Para ejercer estos derechos, puede contactar a través del correo electrónico o teléfono indicados anteriormente.</p>

<hr>

<h3 style="text-align:center;">CONSENTIMIENTO</h3>

<p>D/Dña. <strong>[NOMBRE_PACIENTE]</strong>, con teléfono <strong>[TELEFONO_PACIENTE]</strong>,</p>

<p>DECLARO que he sido informado/a de las condiciones del tratamiento de mis datos personales y CONSENTO el tratamiento de los mismos para las finalidades descritas en este documento.</p>

<br>

<p>Firma de la persona paciente:</p>
<br><br>
<p>_____________________________</p>

<br>

<p>Firma de la profesional:</p>
<br><br>
<p>_____________________________</p>
<p>[NOMBRE_PSICOLOGA]</p>';
    }
}
