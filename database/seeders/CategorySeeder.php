<?php

namespace Database\Seeders;

use App\Enums\CategoryEnum;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //criação e utilização de enum para facilitar a utilização no código
        $categories = CategoryEnum::cases();

        foreach ($categories as $category) {
            //cria a categoria caso o id não exista ainda ou atualiza caso já exista
            Category::updateOrCreate([
                'id' => $category->value,
            ], [
                'id' => $category->value,
                'name' => $category->name(),
            ]);
        }

    }
}
