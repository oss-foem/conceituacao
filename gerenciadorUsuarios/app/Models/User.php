<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; # Utilizando a autenticação do Laravel
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /**
        * Atributos
    */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
        * Verficação se o usuario possui um perfil de Administrador 
    */
    public function isAdmin(){
        return $this->profiles->contains('name', 'Administrador');
    }

    /**
        * Verficação se o usuario possui um perfil de Gerente 
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
        * Obtém os perfis que pertencem a este usuário.
    */
    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class);
    }
}

