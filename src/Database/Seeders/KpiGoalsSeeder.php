<?php

namespace IncadevUns\CoreDomain\Database\Seeders;

use Illuminate\Database\Seeder;
use IncadevUns\CoreDomain\Models\KpiGoal;

class KpiGoalsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Inserta los 4 KPIs iniciales con sus metas predeterminadas.
     * Utiliza firstOrCreate para evitar duplicados en múltiples ejecuciones.
     */
    public function run(): void
    {
        $kpis = [
            [
                'name' => 'satisfaccion_estudiantil',
                'display_name' => 'Satisfacción Estudiantil',
                'goal_value' => 85.00,
                'current_value' => 0,
                'previous_value' => 0,
                'trend' => 0,
                'status' => 'Requiere atención',
            ],
            [
                'name' => 'ejecucion_presupuestal',
                'display_name' => 'Ejecución Presupuestal',
                'goal_value' => 90.00,
                'current_value' => 0,
                'previous_value' => 0,
                'trend' => 0,
                'status' => 'Requiere atención',
            ],
            [
                'name' => 'satisfaccion_instructores',
                'display_name' => 'Satisfacción con Instructores',
                'goal_value' => 88.00,
                'current_value' => 0,
                'previous_value' => 0,
                'trend' => 0,
                'status' => 'Requiere atención',
            ],
            [
                'name' => 'empleabilidad_graduados',
                'display_name' => 'Tasa de Empleabilidad de Graduados',
                'goal_value' => 75.00,
                'current_value' => 0,
                'previous_value' => 0,
                'trend' => 0,
                'status' => 'Requiere atención',
            ],
        ];

        foreach ($kpis as $kpiData) {
            // Usa firstOrCreate para evitar duplicados y hacer el seeder idempotente
            // Busca por 'name' (campo único) y crea solo si no existe
            KpiGoal::firstOrCreate(
                ['name' => $kpiData['name']],
                $kpiData
            );
        }
    }
}
