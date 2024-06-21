<?php

namespace App\Libraries;

use App\Models\Category;

class CategoryManager extends AbstractManager
{
    public function getCategoryByName(string $name) : Category
    {
        return Category::firstOrCreate([
            'name' => $name,
        ]);
    }
}
