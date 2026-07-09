<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();

        $faqs = Faq::where('psicologa_id', $psicologa->id)
            ->orderBy('orden')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.faq.index', compact('faqs'));
    }

    public function store(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();

        $request->validate([
            'pregunta' => 'required|string|max:300',
            'respuesta' => 'required|string',
            'orden' => 'nullable|integer|min:0',
        ]);

        Faq::create([
            'psicologa_id' => $psicologa->id,
            'pregunta' => $request->pregunta,
            'respuesta' => $request->respuesta,
            'orden' => $request->orden ?? 0,
            'activo' => true,
        ]);

        return back()->with('success', 'Pregunta frecuente creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $faq = Faq::where('psicologa_id', $psicologa->id)->findOrFail($id);

        $request->validate([
            'pregunta' => 'required|string|max:300',
            'respuesta' => 'required|string',
            'orden' => 'nullable|integer|min:0',
            'activo' => 'boolean',
        ]);

        $faq->update($request->only(['pregunta', 'respuesta', 'orden', 'activo']));

        return back()->with('success', 'Pregunta frecuente actualizada.');
    }

    public function destroy($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $faq = Faq::where('psicologa_id', $psicologa->id)->findOrFail($id);
        $faq->delete();

        return back()->with('success', 'Pregunta frecuente eliminada.');
    }

    public function toggle($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $faq = Faq::where('psicologa_id', $psicologa->id)->findOrFail($id);
        $faq->update(['activo' => !$faq->activo]);

        return back()->with('success', 'Estado actualizado.');
    }
}
