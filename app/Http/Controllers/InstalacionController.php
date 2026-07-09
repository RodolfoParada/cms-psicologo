<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionWeb;
use App\Models\Especialidad;
use App\Models\Precio;
use App\Models\Psicologa;
use App\Models\Servicio;
use App\Models\Tema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InstalacionController extends Controller
{
    public function paso1()
    {
        return view('instalacion.paso1');
    }

    public function paso1Post(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:psicologas,email',
            'telefono' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        session(['instalacion.paso1' => $request->all()]);

        return redirect()->route('instalacion.paso2');
    }

    public function paso2()
    {
        return view('instalacion.paso2');
    }

    public function paso2Post(Request $request)
    {
        $request->validate([
            'slogan' => 'nullable|string|max:255',
            'numero_colegiado' => 'nullable|string|max:50',
            'telefono_citas' => 'nullable|string|max:20',
            'email_citas' => 'nullable|email|max:255',
            'sobre_mi' => 'nullable|string',
        ]);

        session(['instalacion.paso2' => $request->all()]);

        return redirect()->route('instalacion.paso3');
    }

    public function paso3()
    {
        return view('instalacion.paso3');
    }

    public function paso3Post(Request $request)
    {
        $request->validate([
            'especialidades' => 'nullable|array',
            'especialidades.*.nombre' => 'required|string|max:255',
            'servicios' => 'nullable|array',
            'servicios.*.nombre' => 'required|string|max:255',
        ]);

        session(['instalacion.paso3' => $request->all()]);

        return redirect()->route('instalacion.paso4');
    }

    public function paso4()
    {
        return view('instalacion.paso4');
    }

    public function paso4Post(Request $request)
    {
        $request->validate([
            'precios_online' => 'nullable|array',
            'precios_online.*.nombre' => 'required|string|max:255',
            'precios_online.*.precio_mensual' => 'nullable|numeric|min:0',
            'precios_online.*.precio_anual' => 'nullable|numeric|min:0',
            'precios_presencial' => 'nullable|array',
            'precios_presencial.*.nombre' => 'required|string|max:255',
            'precios_presencial.*.precio_mensual' => 'nullable|numeric|min:0',
            'precios_presencial.*.precio_anual' => 'nullable|numeric|min:0',
        ]);

        session(['instalacion.paso4' => $request->all()]);

        return redirect()->route('instalacion.paso5');
    }

    public function paso5()
    {
        return view('instalacion.paso5');
    }

    public function paso5Post(Request $request)
    {
        $request->validate([
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:10',
            'pais' => 'nullable|string|max:255',
        ]);

        session(['instalacion.paso5' => $request->all()]);

        return redirect()->route('instalacion.paso6');
    }

    public function paso6()
    {
        return view('instalacion.paso6');
    }

    public function paso6Post(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('psicologas', 'public');
        }

        session(['instalacion.paso6' => ['foto' => $fotoPath]]);

        return redirect()->route('instalacion.paso7');
    }

    public function paso7()
    {
        $temas = Tema::where('activo', true)->get();
        return view('instalacion.paso7', compact('temas'));
    }

    public function completar(Request $request)
    {
        $request->validate([
            'tema_id' => 'required|exists:temas,id',
        ]);

        $dataPaso1 = session('instalacion.paso1');
        $dataPaso2 = session('instalacion.paso2');
        $dataPaso3 = session('instalacion.paso3');
        $dataPaso4 = session('instalacion.paso4');
        $dataPaso5 = session('instalacion.paso5');
        $dataPaso6 = session('instalacion.paso6');

        if (!$dataPaso1) {
            return redirect()->route('instalacion.paso1');
        }

        $psicologa = Psicologa::create([
            'nombre' => $dataPaso1['nombre'],
            'apellidos' => $dataPaso1['apellidos'],
            'email' => $dataPaso1['email'],
            'telefono' => $dataPaso1['telefono'],
            'password' => $dataPaso1['password'],
            'slogan' => $dataPaso2['slogan'] ?? null,
            'numero_colegiado' => $dataPaso2['numero_colegiado'] ?? null,
            'telefono_citas' => $dataPaso2['telefono_citas'] ?? null,
            'email_citas' => $dataPaso2['email_citas'] ?? null,
            'sobre_mi' => $dataPaso2['sobre_mi'] ?? null,
            'foto' => $dataPaso6['foto'] ?? null,
            'direccion' => $dataPaso5['direccion'] ?? null,
            'ciudad' => $dataPaso5['ciudad'] ?? null,
            'codigo_postal' => $dataPaso5['codigo_postal'] ?? null,
            'pais' => $dataPaso5['pais'] ?? null,
            'tema_id' => $request->tema_id,
        ]);

        if (!empty($dataPaso3['especialidades'])) {
            foreach ($dataPaso3['especialidades'] as $item) {
                Especialidad::create([
                    'psicologa_id' => $psicologa->id,
                    'nombre' => $item['nombre'],
                    'descripcion' => $item['descripcion'] ?? null,
                ]);
            }
        }

        if (!empty($dataPaso3['servicios'])) {
            foreach ($dataPaso3['servicios'] as $item) {
                Servicio::create([
                    'psicologa_id' => $psicologa->id,
                    'nombre' => $item['nombre'],
                    'descripcion' => $item['descripcion'] ?? null,
                ]);
            }
        }

        if (!empty($dataPaso4['precios_online'])) {
            foreach ($dataPaso4['precios_online'] as $item) {
                Precio::create([
                    'psicologa_id' => $psicologa->id,
                    'tipo' => 'online',
                    'nombre' => $item['nombre'],
                    'precio_mensual' => $item['precio_mensual'] ?? null,
                    'precio_anual' => $item['precio_anual'] ?? null,
                    'descripcion' => $item['descripcion'] ?? null,
                ]);
            }
        }

        if (!empty($dataPaso4['precios_presencial'])) {
            foreach ($dataPaso4['precios_presencial'] as $item) {
                Precio::create([
                    'psicologa_id' => $psicologa->id,
                    'tipo' => 'presencial',
                    'nombre' => $item['nombre'],
                    'precio_mensual' => $item['precio_mensual'] ?? null,
                    'precio_anual' => $item['precio_anual'] ?? null,
                    'descripcion' => $item['descripcion'] ?? null,
                ]);
            }
        }

        ConfiguracionWeb::create([
            'psicologa_id' => $psicologa->id,
        ]);

        Auth::guard('psicologa')->login($psicologa);

        session()->forget(['instalacion.paso1', 'instalacion.paso2', 'instalacion.paso3', 'instalacion.paso4', 'instalacion.paso5', 'instalacion.paso6']);

        return redirect('/panel-psicologa');
    }
}
