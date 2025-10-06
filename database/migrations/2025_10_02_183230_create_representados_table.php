<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('representados', function (Blueprint $table) {
            $table->id();
            $table->string('ci')->nullable()->unique();
            $table->date('fecha_nacimiento');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('telefono')->nullable();
            $table->text('direccion')->nullable();
            $table->string('nivel_academico');
            $table->string('foto')->nullable();
            // Cambiar representante_id por user_id
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('representados');
    }
};