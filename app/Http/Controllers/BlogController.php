<?php

namespace App\Http\Controllers;

use App\Models\BlogArticulo;
use App\Models\BlogCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $psicologa = Auth::guard('psicologa')->user();

        $articulos = BlogArticulo::where('psicologa_id', $psicologa->id)
            ->with('categoria')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.blog.index', compact('articulos'));
    }

    public function crear()
    {
        $categorias = BlogCategoria::orderBy('nombre')->get();
        return view('dashboard.blog.form', compact('categorias'));
    }

    public function guardar(Request $request)
    {
        $psicologa = Auth::guard('psicologa')->user();

        $request->validate([
            'titulo' => 'required|string|max:200',
            'contenido' => 'required|string',
            'extracto' => 'nullable|string|max:300',
            'categoria_id' => 'nullable|exists:blog_categorias,id',
            'publicado' => 'boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $slug = Str::slug($request->titulo) . '-' . uniqid();

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('blog', 'public');
        }

        BlogArticulo::create([
            'psicologa_id' => $psicologa->id,
            'categoria_id' => $request->categoria_id,
            'titulo' => $request->titulo,
            'slug' => $slug,
            'contenido' => $request->contenido,
            'extracto' => $request->extracto,
            'imagen' => $imagenPath,
            'publicado' => $request->boolean('publicado'),
            'fecha_publicacion' => $request->boolean('publicado') ? now() : null,
        ]);

        return redirect()->route('blog.index')
            ->with('success', 'Artículo creado correctamente.');
    }

    public function editar($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $articulo = BlogArticulo::where('psicologa_id', $psicologa->id)
            ->findOrFail($id);
        $categorias = BlogCategoria::orderBy('nombre')->get();

        return view('dashboard.blog.form', compact('articulo', 'categorias'));
    }

    public function actualizar(Request $request, $id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $articulo = BlogArticulo::where('psicologa_id', $psicologa->id)
            ->findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:200',
            'contenido' => 'required|string',
            'extracto' => 'nullable|string|max:300',
            'categoria_id' => 'nullable|exists:blog_categorias,id',
            'publicado' => 'boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only([
            'titulo', 'contenido', 'extracto', 'categoria_id',
        ]);

        $data['publicado'] = $request->boolean('publicado');

        if ($data['publicado'] && !$articulo->fecha_publicacion) {
            $data['fecha_publicacion'] = now();
        }

        if ($request->hasFile('imagen')) {
            if ($articulo->imagen) {
                Storage::disk('public')->delete($articulo->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('blog', 'public');
        }

        $articulo->update($data);

        return redirect()->route('blog.index')
            ->with('success', 'Artículo actualizado correctamente.');
    }

    public function eliminar($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $articulo = BlogArticulo::where('psicologa_id', $psicologa->id)
            ->findOrFail($id);

        if ($articulo->imagen) {
            Storage::disk('public')->delete($articulo->imagen);
        }

        $articulo->delete();

        return redirect()->route('blog.index')
            ->with('success', 'Artículo eliminado.');
    }

    public function togglePublicado($id)
    {
        $psicologa = Auth::guard('psicologa')->user();
        $articulo = BlogArticulo::where('psicologa_id', $psicologa->id)
            ->findOrFail($id);

        $articulo->update([
            'publicado' => !$articulo->publicado,
            'fecha_publicacion' => !$articulo->publicado ? now() : $articulo->fecha_publicacion,
        ]);

        return back()->with('success', 'Estado de publicación actualizado.');
    }
}
