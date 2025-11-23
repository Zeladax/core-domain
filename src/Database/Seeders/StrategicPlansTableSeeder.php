<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use IncadevUns\CoreDomain\Models\StrategicPlan;

class StrategicPlansTableSeeder extends Seeder
{
    public function run(): void
    {
        StrategicPlan::create([
            'title' => 'Plan Estratégico Institucional 2025-2027',
            'description' => 'Líneas estratégicas: calidad académica, alianzas y transformación digital.',
            'start_date' => '2025-01-01',
            'end_date' => '2027-12-31',
            'status' => 'vigente',
            'user_id' => 2,
        ]);

        StrategicPlan::create([
            'title' => 'Plan de Transformación Digital 2025-2026',
            'description' => 'Modernización de procesos, capacitación docente y adopción de plataformas.',
            'start_date' => '2025-03-01',
            'end_date' => '2026-12-31',
            'status' => 'en_ejecucion',
            'user_id' => 3,
        ]);
    }
}
