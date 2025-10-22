<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionsTableSeeder extends Seeder
{
    public function run(): void
    {
        Section::truncate();

        $sections = [
            ['name' => 'Slider', 'sl' => 1, 'status' => 1],
        ];

        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}