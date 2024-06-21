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
        if (Schema::hasTable('documents')) { //adicionada verificação de existência da tabela categories para evitar erro ao rodar a migration
            Schema::table('documents', function (Blueprint $table) {
                if (!Schema::hasColumn('documents', 'id')) {
                    $table->id();
                }
                if (!Schema::hasColumn('documents', 'category_id')) {
                    $table->foreignId('category_id') //simplificação na chamada da associação
                    ->constrained('categories')
                        ->cascadeOnDelete();
                }
                if (!Schema::hasColumn('documents', 'title')) {
                    $table->string('title', 60);
                }
                if (!Schema::hasColumn('documents', 'financial_year')) {
                    $table->integer('financial_year'); //adicionado o exercício por estar no arquivo
                }
                if (!Schema::hasColumn('documents', 'contents')) {
                    $table->text('contents');
                }
                if (!Schema::hasColumn('documents', 'created_at')) {
                    $table->timestamps();
                }
            });
        } else {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id') //simplificação na chamada da associação
                    ->constrained('categories')
                    ->cascadeOnDelete();
                $table->string('title', 60);
                $table->integer('financial_year'); //adicionado o exercício por estar no arquivo
                $table->text('contents');
                $table->timestamps(); //como boa prática, coloquei os campos timestamp no fim
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
