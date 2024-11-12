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
        Schema::create('ctc_personas', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->boolean('aprobado')->default(false);
            $table->date('fecha_aprobacion')->nullable()->default(null);
            $table->date('fecha_nacimiento')->default('1900-01-01');
            $table->date('fecha_expedicion_documento')->nullable()->default(null);

            $table->foreignId('genero_id')->constrained('ctc_generos')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreignId('estado_civil_id')->constrained('ctc_estados_civiles')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreignId('municipio_residencia_id')
                ->constrained('ctc_municipios')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreignId('municipio_nacimiento_id')
                ->constrained('ctc_municipios')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->boolean('inactivo')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctc_personas');
    }
};
