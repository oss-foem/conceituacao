@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Bem-vindo ao Gerenciador de Usuários e Perfis') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h2>Objetivo</h2>
                        <p>Criar uma aplicação Laravel para gerenciar usuários e seus perfis, onde cada usuário pode ter múltiplos perfis.</p>

                        <h2>Requisitos</h2>
                        <ul>
                            <li>Autenticação de Usuário</li>
                            <li>Gerenciamento de Usuários</li>
                            <li>Gerenciamento de Perfis</li>
                            <li>Relacionamento Usuário-Perfis</li>
                            <li>Controle de Acesso</li>
                        </ul>

                        <h2>Funcionalidades</h2>
                        <ul>
                            <li>Criar, editar e excluir usuários</li>
                            <li>Criar, editar e excluir perfis</li>
                            <li>Associar e desassociar perfis a usuários</li>
                            <li>Listar os perfis de um usuário</li>
                        </ul>

                        <h2>Ações</h2>
                        <ul>
                            <li><a href="{{ route('users.index') }}">Gerenciar usuários</a></li>
                            <li><a href="{{ route('profiles.index') }}">Gerenciar perfis</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
