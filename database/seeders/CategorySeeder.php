<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = [
            [
                'name' => 'легкий'
            ],
            [
                'name' => 'хрупкий'
            ],
            [
                'name' => 'тяжелый'
            ]
        ];

        foreach ($category as $key => $value) {
            Category::create($value);
        }
    }
}
