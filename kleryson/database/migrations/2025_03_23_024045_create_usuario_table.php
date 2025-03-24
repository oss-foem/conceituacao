<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->bigIncrements('COD_USUARIO');
            $table->string('NOM_USUARIO', 255);
            $table->string('DSC_USUARIO', 100);
            $table->string('DSC_SENHA', 255);
            $table->string('DSC_EMAIL', 255);
            $table->biginteger('COD_PERFIL');
            $table->tinyinteger('IND_ATIVO')->default(1);
            $table->dateTime('created_at', $precision = 0);
            $table->dateTime('update_at', $precision = 0);
            $table->softDeletesTz($column = 'deleted_at', $precision = 0);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
