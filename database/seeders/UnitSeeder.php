<?php

namespace Database\Seeders;

use App\Faker\UnitProvider;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = UnitProvider::getUnits(); 
        foreach ($units as $unit) {
            Unit::firstOrCreate([
                'name' => $unit,
            ]);
        }
    }
}
