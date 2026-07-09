<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionWeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionesController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        if (!$config) {
            $config = ConfiguracionWeb::create(['psicologa_id' => $psicologa->id]);
        }

        return view('dashboard.notificaciones.index', compact('config'));
    }

    public function guardar(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();

        $request->validate([
            'email_notificaciones' => 'nullable|email|max:255',
            'email_smtp_host' => 'nullable|string|max:255',
            'email_smtp_port' => 'nullable|string|max:10',
            'email_smtp_user' => 'nullable|string|max:255',
            'email_smtp_pass' => 'nullable|string|max:255',
            'email_smtp_encryption' => 'nullable|in:tls,ssl',
            'notificaciones_activas' => 'boolean',
        ]);

        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        if (!$config) {
            $config = ConfiguracionWeb::create(['psicologa_id' => $psicologa->id]);
        }

        $config->update([
            'email_notificaciones' => $request->email_notificaciones,
            'email_smtp_host' => $request->email_smtp_host,
            'email_smtp_port' => $request->email_smtp_port,
            'email_smtp_user' => $request->email_smtp_user,
            'email_smtp_pass' => $request->filled('email_smtp_pass') ? $request->email_smtp_pass : $config->email_smtp_pass,
            'email_smtp_encryption' => $request->email_smtp_encryption,
            'notificaciones_activas' => $request->boolean('notificaciones_activas'),
        ]);

        return back()->with('success', 'Configuración de email guardada.');
    }
}
