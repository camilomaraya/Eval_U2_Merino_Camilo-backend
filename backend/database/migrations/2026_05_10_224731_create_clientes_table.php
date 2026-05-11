<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table): void {
            $table->id();                              // INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('rut', 12)->unique();       // RUT chileno, único
            $table->string('nombre', 50);              // Nombre del cliente
            $table->string('apellido', 50);            // Apellido del cliente
            $table->string('email', 100)->unique();    // Email, único
            $table->string('telefono', 20)->nullable(); // Teléfono, opcional
            $table->timestamps();                      // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
