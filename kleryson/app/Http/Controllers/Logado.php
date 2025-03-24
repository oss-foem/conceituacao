<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Logado extends Controller
{
    
    public function Logado(Request $request) {
        $objSession =  $request->session()->all();;
        if(empty($objSession['Authenticado'])) {
             $request->session()->forget('Authenticado');
             return redirect('/');
        }


        return view('logado');
    }
}
