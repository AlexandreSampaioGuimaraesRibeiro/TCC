<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Diarista',            'icon' => '🧹', 'requires_qualification' => false],
            ['name' => 'Pedreiro',            'icon' => '🧱', 'requires_qualification' => false],
            ['name' => 'Eletricista',         'icon' => '⚡', 'requires_qualification' => true],
            ['name' => 'Bombeiro Hidráulico', 'icon' => '🔧', 'requires_qualification' => true],
            ['name' => 'Jardineiro',          'icon' => '🌿', 'requires_qualification' => false],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                $cat + ['slug' => Str::slug($cat['name'])]
            );
        }
    }
}
