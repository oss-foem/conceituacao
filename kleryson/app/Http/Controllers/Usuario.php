<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModelUsuario;
use App\Models\ModelPerfil;

use DB;
use Redirect;

class Usuario extends Controller
{
    public function Logar(Request $request) {
        $strUsuario = $request->get('usuario');
        $strSenha = $request->get('senha');

        try{
            $objUsuario = new ModelUsuario();
            $objDadosUsuario = $objUsuario->logarUSuario($strUsuario, md5($strSenha));

            $request->session()->put('Authenticado', true);
            $request->session()->put('Usuario', $objDadosUsuario->DSC_USUARIO);
            $request->session()->put('Perfil', 1);

            return redirect('/logado');
        } catch (Exception $e) {
            var_dump($e);
        }
        
    }

    public function Listagem(Request $request) {
        $objSession =  $request->session()->all();;
        if(empty($objSession['Authenticado'])) {
             $request->session()->forget('Authenticado');
             return redirect('/');
        }

        $mensagemAlert = (!empty($objSession['mensagemAlert'])? $objSession['mensagemAlert'] : ['message' => '', 'typeMessage' => '']);
        $request->session()->forget('mensagemAlert');

        try{
            $objUsuario = new ModelUsuario();
            $objDadosUsuario = $objUsuario->listagem();

            return view('usuariolistage')
                ->with('arrUsuario', $objDadosUsuario)
                ->with('mensagemAlert', $mensagemAlert['message'])
                ->with('typeAlert', $mensagemAlert['typeMessage'])
            ;
        } catch (Exception $e) {
            var_dump($e);
        }
        
    }

    public function Logout(Request $request) {
        $request->session()->forget('Authenticado');
        return redirect('/');
    }

    public function Cadastro(Request $request) {
        $objSession =  $request->session()->all();;
        if(empty($objSession['Authenticado'])) {
             $request->session()->forget('Authenticado');
             return redirect('/');
        }

        try{
            $objUsuario = new ModelUsuario();
            $objDadosUsuario = $objUsuario->listagem();

            $objPerfil = new ModelPerfil();
            $objDadosPerfil = $objPerfil->getPerfil();

            return view('usuarioCadastro')
                ->with('arrPerfil', $objDadosPerfil)
            ;
        } catch (Exception $e) {
            var_dump($e);
        }
        
    }

    public function Excluir(Request $request, $id) {
        try{
            $objUsuario = new ModelUsuario();
            $objUsuario->excluir($id);

            $request->session()->put('mensagemAlert', ['message' => 'Usuário deletado com sucesso', 'typeMessage' => 'alert-success']);

        }catch (Exception $e) {
            $request->session()->put('mensagemAlert', ['message' => 'Houve um erro ao deletar o usuario', 'typeMessage' => 'alert-danger']);

            var_dump($e);
        }     
        return redirect('/usuario');
    }

    public function Salvar(Request $request) {
        try {
            $idUsuario = $request->get('COD_USUARIO');
            $arrDados = [
                    'NOM_USUARIO' => $request->get('nome'),
                    'DSC_USUARIO' => $request->get('usuario'),
                    'DSC_EMAIL' => $request->get('email'),
                    'DSC_SENHA' => md5($request->get('senha')),
                    'COD_PERFIL' => $request->get('perfil')
            ];

            $objUsuario = new ModelUsuario();
            $objUsuario->salvar($arrDados, $idUsuario);

            $request->session()->put('mensagemAlert', ['message' => (empty($idUsuario)? 'Usuário cadastrado com sucesso' : 'Usuário editado com sucesso'), 'typeMessage' => 'alert-success']);

        } catch (Exception $e) {
            $request->session()->put('mensagemAlert', ['message' => (empty($idUsuario)? 'Houve um erro ao cadastrar usuário ('. $e->getMesage() .')' : 'Houve um erro ao editar usuário ('. $e->getMesage() .')'), 'typeMessage' => 'alert-danger']);
            
        }

        return redirect('/usuario');
    }
}
