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
        Schema::create('ctc_personas_information', function (Blueprint $table) {
            $table->id();

            $table->foreignId('persona_id')->constrained('ctc_personas')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->json('respuestas')->nullable();
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->mediumText('observacion')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctc_persona_information');
    }
};
