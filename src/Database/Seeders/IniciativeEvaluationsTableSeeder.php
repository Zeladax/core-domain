<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use IncadevUns\CoreDomain\Models\IniciativeEvaluation;

class IniciativeEvaluationsTableSeeder extends Seeder
{
    public function run(): void
    {
        IniciativeEvaluation::create([
            'iniciative_id' => 1,
            'evaluator_user' => 5,   // evaluador existente
            'summary' => 'Se evidencian avances en convenios y primeras incorporaciones.',
            'score' => 85.50,
            'document_id' => 2,   // "Matriz de KPIs 2025"
        ]);

        IniciativeEvaluation::create([
            'iniciative_id' => 3,
            'evaluator_user' => 6,
            'summary' => 'Migración con hitos cumplidos; pendiente optimizar costos.',
            'score' => 78.25,
            'document_id' => 1,   // "Acta de Consejo Académico 001-2025"
        ]);

        IniciativeEvaluation::create([
            'iniciative_id' => 2,
            'evaluator_user' => 5,
            'summary' => 'Células formadas; falta cerrar el plan de evidencias por escuela.',
            'score' => 72.00,
            'document_id' => 3,   // "Política de Calidad Educativa"
        ]);
    }
}
