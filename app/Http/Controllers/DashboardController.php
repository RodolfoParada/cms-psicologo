<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Psicologa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function inicio()
    {
        $psicologa = Auth::guard('psicologa')->user();

        $citasHoy = Cita::where('psicologa_id', $psicologa->id)
            ->whereDate('fecha', today())
            ->count();

        $totalCitas = Cita::where('psicologa_id', $psicologa->id)->count();
        $citasPendientes = Cita::where('psicologa_id', $psicologa->id)
            ->where('estado', 'pendiente')
            ->count();

        $proximasCitas = Cita::where('psicologa_id', $psicologa->id)
            ->where('fecha', '>=', today())
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->take(5)
            ->get();

        return view('dashboard.inicio', compact(
            'citasHoy', 'totalCitas', 'citasPendientes', 'proximasCitas'
        ));
    }
}
