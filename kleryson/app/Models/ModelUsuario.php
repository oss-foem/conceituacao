<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class ModelUsuario extends Model
{
    use HasFactory;

    protected $strTable = 'usuario';

    public function logarUSuario($strUsuario, $strPass) {

        $objUsuario = DB::table($this->strTable)
            ->where('DSC_USUARIO', $strUsuario)
            ->where('DSC_SENHA', $strPass)
            ->where('IND_ATIVO', 1)
            ->get();

        var_dump($objUsuario);

        if(sizeof($objUsuario) == 0)
            throw new Exception ('Usuario ou senha incorreto.');

        return $objUsuario[0];
    }

    public function listagem() {
        $objUsuario = DB::table($this->strTable)
            ->WhereNull('deleted_at')
            ->get();

        if(sizeof($objUsuario) == 0)
            throw new Exception ('Não tem nenhum usuário cadastrado.');

        return $objUsuario->toArray();
    }

    public function excluir($id) {
        try{
            DB::table($this->strTable)->where('COD_USUARIO', $id)
                ->update(['deleted_at' => date('Y-m-d H:i:s'), 'IND_ATIVO' => 0])
            ;

            return true;
        } catch (Exception $e){
            throw $e;
        }
    }

    public function salvar($arrDados, $idUsuario) {
        if(empty($idUsuario)) {
            $arrDados['created_at'] = date("Y-m-d H:i:s");
            $arrDados['update_at'] = date("Y-m-d H:i:s");
            DB::table($this->strTable)->insert($arrDados);
        } else {
            $arrDados['update_at'] = date("Y-m-d H:i:s");
            DB::table($this->strTable)->where('COD_USUARIO', $idUsuario)
                ->update($arrDados);
        }

        return true;
    }
}
