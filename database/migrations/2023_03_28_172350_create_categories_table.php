<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('categories')) { //adicionada verificação de existência da tabela categories para evitar erro ao rodar a migration
            Schema::table('categories', function (Blueprint $table) {
                if (!Schema::hasColumn('categories', 'name')) {
                    $table->string('name', 20)->unique();
                }
                if (!Schema::hasColumn('categories', 'created_at')) {
                    $table->timestamps();
                }
            });
        } else {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 20)->unique(); //unicidade adicionada
                $table->timestamps(); //como boa prática, coloquei os campos timestamp no fim
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
