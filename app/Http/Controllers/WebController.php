<?php

namespace App\Http\Controllers;

use App\Models\BlogArticulo;
use App\Models\BlogCategoria;
use App\Models\ConfiguracionWeb;
use App\Models\Especialidad;
use App\Models\Faq;
use App\Models\FrasePublica;
use App\Models\Precio;
use App\Models\RedSocial;
use App\Models\Servicio;
use App\Models\Psicologa;
use App\Models\Tema;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        $psicologa = Psicologa::first();
        if (!$psicologa) {
            return redirect()->route('instalacion.paso1');
        }
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        $tema = Tema::find($psicologa->tema_id);
        $carpeta = $tema?->carpeta ?? 'tema-base';

        $servicios = Servicio::where('psicologa_id', $psicologa->id)->orderBy('orden')->get();
        $especialidades = Especialidad::where('psicologa_id', $psicologa->id)->orderBy('orden')->get();
        $preciosOnline = Precio::where('psicologa_id', $psicologa->id)->where('tipo', 'online')->orderBy('orden')->get();
        $preciosPresencial = Precio::where('psicologa_id', $psicologa->id)->where('tipo', 'presencial')->orderBy('orden')->get();
        $faqs = Faq::where('psicologa_id', $psicologa->id)->where('activo', true)->orderBy('orden')->get();
        $articulos = BlogArticulo::where('psicologa_id', $psicologa->id)->where('publicado', true)->orderBy('created_at', 'desc')->limit(3)->get();
        $redes = RedSocial::where('psicologa_id', $psicologa->id)->where('activo', true)->get();
        $frases = FrasePublica::where('psicologa_id', $psicologa->id)->get()->keyBy('clave');

        $view = $config?->modo_visualizacion === 'multipagina' ? "themes.{$carpeta}.multipagina" : "themes.{$carpeta}.index";

        return view($view, compact(
            'psicologa', 'config', 'tema', 'carpeta',
            'servicios', 'especialidades', 'preciosOnline', 'preciosPresencial',
            'faqs', 'articulos', 'redes', 'frases'
        ));
    }

    public function sobreMi()
    {
        $psicologa = Psicologa::firstOrFail();
        $tema = Tema::find($psicologa->tema_id);
        $carpeta = $tema?->carpeta ?? 'tema-base';
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        $redes = RedSocial::where('psicologa_id', $psicologa->id)->where('activo', true)->get();
        $frases = FrasePublica::where('psicologa_id', $psicologa->id)->get()->keyBy('clave');

        return view("themes.{$carpeta}.sobre-mi", compact('psicologa', 'tema', 'carpeta', 'config', 'redes', 'frases'));
    }

    public function servicios()
    {
        $psicologa = Psicologa::firstOrFail();
        $tema = Tema::find($psicologa->tema_id);
        $carpeta = $tema?->carpeta ?? 'tema-base';
        $servicios = Servicio::where('psicologa_id', $psicologa->id)->orderBy('orden')->get();
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        $redes = RedSocial::where('psicologa_id', $psicologa->id)->where('activo', true)->get();
        $frases = FrasePublica::where('psicologa_id', $psicologa->id)->get()->keyBy('clave');

        return view("themes.{$carpeta}.servicios", compact('psicologa', 'tema', 'carpeta', 'servicios', 'config', 'redes', 'frases'));
    }

    public function blog(Request $request)
    {
        $psicologa = Psicologa::firstOrFail();
        $tema = Tema::find($psicologa->tema_id);
        $carpeta = $tema?->carpeta ?? 'tema-base';
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        $redes = RedSocial::where('psicologa_id', $psicologa->id)->where('activo', true)->get();
        $frases = FrasePublica::where('psicologa_id', $psicologa->id)->get()->keyBy('clave');

        if (! $config?->mostrar_blog) {
            abort(404);
        }

        $query = BlogArticulo::where('psicologa_id', $psicologa->id)->where('publicado', true);

        if ($request->filled('categoria')) {
            $query->whereHas('categoria', function ($q) use ($request) {
                $q->where('slug', $request->categoria);
            });
        }

        $articulos = $query->orderBy('created_at', 'desc')->paginate(6)->withQueryString();
        $categorias = BlogCategoria::whereHas('articulos', function ($q) use ($psicologa) {
            $q->where('psicologa_id', $psicologa->id)->where('publicado', true);
        })->orderBy('nombre')->get();

        return view("themes.{$carpeta}.blog", compact(
            'psicologa', 'tema', 'carpeta', 'articulos', 'config', 'redes', 'frases', 'categorias'
        ));
    }

    public function blogArticulo($slug)
    {
        $psicologa = Psicologa::firstOrFail();
        $tema = Tema::find($psicologa->tema_id);
        $carpeta = $tema?->carpeta ?? 'tema-base';
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        $redes = RedSocial::where('psicologa_id', $psicologa->id)->where('activo', true)->get();
        $frases = FrasePublica::where('psicologa_id', $psicologa->id)->get()->keyBy('clave');

        if (! $config?->mostrar_blog) {
            abort(404);
        }

        $articulo = BlogArticulo::where('slug', $slug)->where('publicado', true)->firstOrFail();

        return view("themes.{$carpeta}.articulo", compact('psicologa', 'tema', 'carpeta', 'articulo', 'config', 'redes', 'frases'));
    }

    public function faq()
    {
        $psicologa = Psicologa::firstOrFail();
        $tema = Tema::find($psicologa->tema_id);
        $carpeta = $tema?->carpeta ?? 'tema-base';
        $faqs = Faq::where('psicologa_id', $psicologa->id)->where('activo', true)->orderBy('orden')->get();
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        $redes = RedSocial::where('psicologa_id', $psicologa->id)->where('activo', true)->get();
        $frases = FrasePublica::where('psicologa_id', $psicologa->id)->get()->keyBy('clave');

        return view("themes.{$carpeta}.faq", compact('psicologa', 'tema', 'carpeta', 'faqs', 'config', 'redes', 'frases'));
    }

    public function contacto()
    {
        $psicologa = Psicologa::firstOrFail();
        $tema = Tema::find($psicologa->tema_id);
        $carpeta = $tema?->carpeta ?? 'tema-base';
        $config = ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        $redes = RedSocial::where('psicologa_id', $psicologa->id)->where('activo', true)->get();
        $frases = FrasePublica::where('psicologa_id', $psicologa->id)->get()->keyBy('clave');

        return view("themes.{$carpeta}.contacto", compact('psicologa', 'tema', 'carpeta', 'config', 'redes', 'frases'));
    }
}
