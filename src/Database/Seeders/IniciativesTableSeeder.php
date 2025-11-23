<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use IncadevUns\CoreDomain\Models\Iniciative;

class IniciativesTableSeeder extends Seeder
{
    public function run(): void
    {
        Iniciative::create([
            'title' => 'Programa de prácticas con empresas locales',
            'plan_id' => 1,
            'summary' => 'Fortalecer inserción laboral mediante convenios de prácticas.',
            'user_id' => 2,
            'status' => 'en_progreso',
            'start_date' => '2025-02-01',
            'end_date' => '2025-12-15',
            'estimated_impact' => 'Alto',
        ]);

        Iniciative::create([
            'title' => 'Célula de acreditación por escuela',
            'plan_id' => 1,
            'summary' => 'Equipos dedicados a evidencias y mejora continua.',
            'user_id' => 4,
            'status' => 'pendiente',
            'start_date' => '2025-03-01',
            'end_date' => '2026-03-01',
            'estimated_impact' => 'Medio',
        ]);

        Iniciative::create([
            'title' => 'Migración de servicios a la nube',
            'plan_id' => 2,
            'summary' => 'Priorizar sistemas académicos y portal institucional.',
            'user_id' => 2,
            'status' => 'en_progreso',
            'start_date' => '2025-04-01',
            'end_date' => '2026-01-31',
            'estimated_impact' => 'Alto',
        ]);

        Iniciative::create([
            'title' => 'Capacitación docente en aulas virtuales',
            'plan_id' => 2,
            'summary' => 'Talleres mensuales y certificaciones.',
            'user_id' => 4,
            'status' => 'pendiente',
            'start_date' => '2025-05-15',
            'end_date' => '2025-11-30',
            'estimated_impact' => 'Medio',
        ]);
    }
}
