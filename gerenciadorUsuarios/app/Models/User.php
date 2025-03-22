<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; # Utilizando a autenticação do Laravel
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
        * Verficação se o usuario possui um perfil de Administrador para acesso ao sistema de gereciamento de perfis
    */
    public function isAdmin(){
        return $this->profiles->contains('name', 'Administrador');
    }

    /**
        * Verficação se o usuario possui um perfil de Gerente para cadastrar novos usuários
    */
    public function isManager(){
        return $this->profiles->contains(function($profile){
            return str_contains($profile->name, 'Gerente');
        });
    }

    /**
        * Verificação se o usuario não possui nenhum perfil
    */
    public function noProfile(){
        return $this->profiles->isEmpty();
    }

    /**
        * Relação muitos-para-muitos com perfis
    */
    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class);
    }
}

