<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use IncadevUns\CoreDomain\Models\StrategicObjective;

class StrategicObjectivesTableSeeder extends Seeder
{
    public function run(): void
    {
        StrategicObjective::create([
            'plan_id' => 1,
            'title' => 'Incrementar empleabilidad de egresados',
            'description' => 'Alianzas de prácticas, ferias laborales y seguimiento a egresados.',
            'goal_value' => 0.90,   // 90%
            'user_id' => 2,
            'weight' => 40,
            'kpis' => [
                ['name' => 'Tasa empleabilidad', 'target' => 90, 'unit' => '%'],
                ['name' => 'Convenios activos', 'target' => 20, 'unit' => 'n'],
            ],
        ]);

        StrategicObjective::create([
            'plan_id' => 1,
            'title' => 'Acreditar programas activos',
            'description' => 'Cumplir estándares y evidencias de calidad para la acreditación.',
            'goal_value' => 1.00,   // 100%
            'user_id' => 4,
            'weight' => 35,
            'kpis' => [
                ['name' => 'Programas acreditados', 'target' => 100, 'unit' => '%'],
                ['name' => 'Hallazgos críticos', 'target' => 0, 'unit' => 'n'],
            ],
        ]);

        StrategicObjective::create([
            'plan_id' => 2,
            'title' => 'Modernizar infraestructura digital',
            'description' => 'Migración de servicios, automatización y capacitación.',
            'goal_value' => 0.80,
            'user_id' => 2,
            'weight' => 25,
            'kpis' => [
                ['name' => 'Servicios migrados', 'target' => 10, 'unit' => 'n'],
                ['name' => 'Usuarios capacitados', 'target' => 200, 'unit' => 'n'],
            ],
        ]);
    }
}
