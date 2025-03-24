<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use DB;
class usuarioADM extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrUsuario = [
            'NOM_USUARIO' => 'Administrador',
            'DSC_USUARIO' => 'admin',
            'DSC_SENHA' => md5('admin'),
            'DSC_EMAIL' => 'admin@teste.com.br',
            'COD_PERFIL' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'update_at' => date('Y-m-d H:i:s'),
        ];

        DB::table('usuario')->insert($arrUsuario);


        $arrPerfil = [
            'NOM_PERFIL' => 'Administrador',
            'IND_ATIVO' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        DB::table('perfil')->insert($arrPerfil);

        $arrPerfil1 = [
            'NOM_PERFIL' => 'Supervisor',
            'IND_ATIVO' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        DB::table('perfil')->insert($arrPerfil1);
    }
}
