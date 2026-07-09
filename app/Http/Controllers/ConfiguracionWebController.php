<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionWeb;
use App\Models\Especialidad;
use App\Models\Precio;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConfiguracionWebController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        if (!$config) {
            $config = ConfiguracionWeb::create(['psicologa_id' => $psicologa->id]);
        }
        $servicios = Servicio::where('psicologa_id', $psicologa->id)->orderBy('orden')->get();
        $especialidades = Especialidad::where('psicologa_id', $psicologa->id)->orderBy('orden')->get();
        $preciosOnline = Precio::where('psicologa_id', $psicologa->id)->where('tipo', 'online')->orderBy('orden')->get();
        $preciosPresencial = Precio::where('psicologa_id', $psicologa->id)->where('tipo', 'presencial')->orderBy('orden')->get();

        return view('dashboard.configuracion-web.index', compact(
            'psicologa', 'config', 'servicios', 'especialidades', 'preciosOnline', 'preciosPresencial'
        ));
    }

    public function guardar(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'numero_colegiado' => 'nullable|string|max:50',
            'telefono_citas' => 'nullable|string|max:20',
            'email_citas' => 'nullable|email|max:255',
            'sobre_mi' => 'nullable|string',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:10',
            'pais' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'modo_visualizacion' => 'required|in:landing,multipagina',
            'meta_descripcion' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'google_maps_url' => 'nullable|string|max:500',
            'mostrar_blog' => 'boolean',
            'mostrar_faq' => 'boolean',
            'mostrar_reservas' => 'boolean',
            'mostrar_especialidades' => 'boolean',
            'mostrar_servicios' => 'boolean',
            'mostrar_testimonios' => 'boolean',
        ]);

        $psicologaData = $request->only([
            'nombre', 'apellidos', 'slogan', 'numero_colegiado',
            'telefono_citas', 'email_citas', 'sobre_mi',
            'direccion', 'ciudad', 'codigo_postal', 'pais',
        ]);

        if ($request->hasFile('foto')) {
            if ($psicologa->foto) {
                Storage::disk('public')->delete($psicologa->foto);
            }
            $psicologaData['foto'] = $request->file('foto')->store('psicologas', 'public');
        }

        $psicologa->update($psicologaData);

        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        if (!$config) {
            $config = ConfiguracionWeb::create(['psicologa_id' => $psicologa->id]);
        }

        $configData = $request->only([
            'modo_visualizacion', 'meta_descripcion', 'meta_keywords', 'google_maps_url',
            'mostrar_blog', 'mostrar_faq', 'mostrar_reservas', 'mostrar_especialidades',
            'mostrar_servicios', 'mostrar_testimonios',
        ]);
        $configData['mostrar_blog'] = $request->boolean('mostrar_blog');
        $configData['mostrar_faq'] = $request->boolean('mostrar_faq');
        $configData['mostrar_reservas'] = $request->boolean('mostrar_reservas');
        $configData['mostrar_especialidades'] = $request->boolean('mostrar_especialidades');
        $configData['mostrar_servicios'] = $request->boolean('mostrar_servicios');
        $configData['mostrar_testimonios'] = $request->boolean('mostrar_testimonios');

        if ($request->hasFile('logo')) {
            if ($config->logo) {
                Storage::disk('public')->delete($config->logo);
            }
            $configData['logo'] = $request->file('logo')->store('configuracion', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($config->favicon) {
                Storage::disk('public')->delete($config->favicon);
            }
            $configData['favicon'] = $request->file('favicon')->store('configuracion', 'public');
        }

        $config->update($configData);

        return redirect()->route('configuracion-web.index')
            ->with('success', 'Configuración web actualizada correctamente.');
    }

    public function guardarDashboardTema(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();

        $request->validate([
            'dashboard_tema' => 'required|in:claro,oscuro',
            'dashboard_color' => 'required|string|max:7',
        ]);

        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        if (!$config) {
            $config = ConfiguracionWeb::create(['psicologa_id' => $psicologa->id]);
        }

        $config->update($request->only(['dashboard_tema', 'dashboard_color']));

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Tema del dashboard actualizado.');
    }
}
