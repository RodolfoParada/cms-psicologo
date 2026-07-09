<?php

namespace App\Http\Controllers;

use App\Models\ImagenWeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImagenesController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();
        $slots = ImagenWeb::slots();
        $imagenes = ImagenWeb::where('psicologa_id', $psicologa->id)
            ->get()
            ->keyBy('clave');

        return view('dashboard.imagenes.index', compact('slots', 'imagenes'));
    }

    public function subir(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|max:100',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $psicologa = Auth::guard('psicologa')->user();
        $clave = $request->clave;

        $imagen = ImagenWeb::where('psicologa_id', $psicologa->id)
            ->where('clave', $clave)
            ->first();

        if ($imagen) {
            Storage::disk('public')->delete($imagen->archivo);
        }

        $archivo = $request->file('imagen')->store('imagenes-web/' . $psicologa->id, 'public');

        ImagenWeb::updateOrCreate(
            ['psicologa_id' => $psicologa->id, 'clave' => $clave],
            [
                'archivo' => $archivo,
                'archivo_original' => $request->file('imagen')->getClientOriginalName(),
                'titulo' => $request->titulo ?? null,
            ]
        );

        return back()->with('success', 'Imagen subida correctamente.');
    }

    public function eliminar($clave)
    {
        $psicologa = Auth::guard('psicologa')->user();

        $imagen = ImagenWeb::where('psicologa_id', $psicologa->id)
            ->where('clave', $clave)
            ->firstOrFail();

        Storage::disk('public')->delete($imagen->archivo);
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada. Se usará la imagen por defecto del tema.');
    }
}
