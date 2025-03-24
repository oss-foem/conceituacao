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
        Schema::create('perfil', function (Blueprint $table) {
            $table->bigIncrements('COD_PERFIL');
            $table->string('NOM_PERFIL', 255);
            $table->tinyinteger('IND_ATIVO')->default(1);
            $table->dateTime('created_at', $precision = 0);
            $table->softDeletesTz($column = 'deleted_at', $precision = 0);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil');
    }
};
