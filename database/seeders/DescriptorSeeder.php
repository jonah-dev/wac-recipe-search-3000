<?php

namespace Database\Seeders;

use App\Faker\DescriptorProvider;
use App\Models\Descriptor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DescriptorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $descriptors = DescriptorProvider::getDescriptors();
        foreach ($descriptors as $descriptor) {
            Descriptor::firstOrCreate([
                'name' => $descriptor,
            ]);
        }
    }
}
