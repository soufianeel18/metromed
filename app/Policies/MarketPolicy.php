<?php

namespace App\Policies;

use App\Market;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->role == 'chef technicien' || $user->role == 'chef commercial' || $user->role == 'commercial' || $user->role == 'technicien';
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Market  $market
     * @return mixed
     */
    public function view(User $user, Market $market)
    {
        return $user->role == 'admin' || $user->role == 'chef technicien' || $user->role == 'chef commercial';
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role == 'admin' || $user->role == 'chef technicien' || $user->role == 'chef commercial';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Market  $market
     * @return mixed
     */
    public function update(User $user, Market $market)
    {
        return $user->role == 'admin' || $user->role == 'chef technicien' || $user->role == 'chef commercial';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Market  $market
     * @return mixed
     */
    public function delete(User $user, Market $market)
    {
        return $user->role == 'admin' || $user->role == 'chef technicien' || $user->role == 'chef commercial';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Market  $market
     * @return mixed
     */
    public function restore(User $user, Market $market)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Market  $market
     * @return mixed
     */
    public function forceDelete(User $user, Market $market)
    {
        //
    }
}
