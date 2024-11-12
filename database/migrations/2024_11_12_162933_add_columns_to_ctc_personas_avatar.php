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
        Schema::table('ctc_personas', function (Blueprint $table) {
            $table->mediumText('avatar')->nullable()->after('municipio_nacimiento_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ctc_personas', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }
};
