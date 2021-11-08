<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Territory;

class TerritoryPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before(){
        return true;
    }

    public function access(User $user, Territory $territory){
        return true;
    }

    public function create(User $user, Territory $territory){
        return true;
    }

    public function update(User $user, Territory $territory){
        return true;
    }

    public function edit(User $user, Territory $territory){
        return true;
    }

    public function view(User $user, Territory $territory){
        return true;
    }

    public function delete(User $user, Territory $territory){
        return true;
    }
}
