<?php

namespace App\Http\Controllers;

use App\Models\BlogCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoriaBlogController extends Controller
{
    public function index()
    {
        $categorias = BlogCategoria::withCount('articulos')
            ->orderBy('nombre')
            ->get();

        return view('dashboard.blog.categorias', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:blog_categorias,nombre',
            'descripcion' => 'nullable|string|max:500',
        ]);

        BlogCategoria::create([
            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre),
            'descripcion' => $request->descripcion,
        ]);

        return back()->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $categoria = BlogCategoria::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:blog_categorias,nombre,' . $id,
            'descripcion' => 'nullable|string|max:500',
        ]);

        $categoria->update([
            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre),
            'descripcion' => $request->descripcion,
        ]);

        return back()->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy($id)
    {
        $categoria = BlogCategoria::findOrFail($id);
        $categoria->articulos()->update(['categoria_id' => null]);
        $categoria->delete();

        return back()->with('success', 'Categoría eliminada.');
    }
}
