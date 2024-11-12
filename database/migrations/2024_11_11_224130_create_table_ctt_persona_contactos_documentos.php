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
        Schema::create('ctt_persona_contactos_documentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('persona_id')
                ->constrained('ctc_personas')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->morphs('model');
            $table->string('valor', 150);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_ctt_persona_contactos_documentos');
    }
};
