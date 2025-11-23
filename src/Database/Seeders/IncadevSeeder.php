<?php

namespace IncadevUns\CoreDomain\Database\Seeders;

use Illuminate\Database\Seeder;

class IncadevSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            UserSeeder::class,
            TechnologySeeder::class,
            AdministrativeSeeder::class,
            AcademicSeeder::class,
            SupportSeeder::class,
            KpiGoalsSeeder::class,
        ]);
    }
}
