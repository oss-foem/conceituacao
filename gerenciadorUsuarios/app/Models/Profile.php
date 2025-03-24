<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Profile extends Model
{
    /**
     * Atributos 
     */
    protected $fillable = ['name', 'description'];

    /**
     * Obtém os usuários que pertencem a este perfil.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
