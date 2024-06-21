<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //chamada da seeder de categoria adicionada para executar sem a necessidade de explicitar a classe na linha de comando
        $this->call(CategorySeeder::class);
    }
}
