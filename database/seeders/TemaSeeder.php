<?php

namespace Database\Seeders;

use App\Models\Tema;
use Illuminate\Database\Seeder;

class TemaSeeder extends Seeder
{
    public function run(): void
    {
        $temas = [
            [
                'nombre' => 'Tema Base',
                'carpeta' => 'tema-base',
                'descripcion' => 'Diseño clásico y profesional en tonos tierra. Ideal para transmitir calidez y confianza.',
                'preview' => '/themes/tema-base/img/psicologa.jpg',
                'color_primario' => '#c9a96e',
                'color_secundario' => '#8b7355',
            ],
            [
                'nombre' => 'Tema Naturaleza',
                'carpeta' => 'tema-naturaleza',
                'descripcion' => 'Tonos verdes y azules, estilo natural y fresco. Conecta con la tranquilidad de la naturaleza.',
                'preview' => null,
                'color_primario' => '#4ade80',
                'color_secundario' => '#06b6d4',
            ],
            [
                'nombre' => 'Tema Lavanda',
                'carpeta' => 'tema-lavanda',
                'descripcion' => 'Tonos morados suaves, estilo relajante y armonioso. Perfecto para transmitir serenidad.',
                'preview' => null,
                'color_primario' => '#a855f7',
                'color_secundario' => '#d946ef',
            ],
            [
                'nombre' => 'Tema Océano',
                'carpeta' => 'tema-oceano',
                'descripcion' => 'Tonos azules profundos, estilo sereno y profesional. Inspirado en la profundidad del océano.',
                'preview' => null,
                'color_primario' => '#0ea5e9',
                'color_secundario' => '#2563eb',
            ],
            [
                'nombre' => 'Tema Rosa',
                'carpeta' => 'tema-rosa',
                'descripcion' => 'Tonos rosados, estilo cálido y acogedor. Aporta un toque dulce y cercano a tu web.',
                'preview' => null,
                'color_primario' => '#ec4899',
                'color_secundario' => '#f43f5e',
            ],
        ];

        foreach ($temas as $tema) {
            Tema::updateOrCreate(
                ['carpeta' => $tema['carpeta']],
                $tema
            );
        }
    }
}
