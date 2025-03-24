<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class ModelPerfil extends Model
{
    use HasFactory;

    protected $strTable = 'Perfil';

    public function getPerfil() {
        $objPerfil = DB::table($this->strTable)
            ->where('IND_ATIVO', 1)
            ->get();

        return $objPerfil->toArray();
    }
}
