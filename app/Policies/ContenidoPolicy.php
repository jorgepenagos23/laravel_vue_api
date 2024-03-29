<?php

namespace App\Policies;

use App\Models\Contenido;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContenidoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contenido $contenido): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user)
    {
        // Verificar si el usuario autenticado es el propietario de la publicación
        return in_array($user->rol, ['director', 'administrador', 'estudiante']);
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contenido $contenido): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Contenido $contenido): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Contenido $contenido): bool
    {
        //
    }
}
