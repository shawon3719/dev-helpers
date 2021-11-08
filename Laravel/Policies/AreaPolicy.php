<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Area;

class AreaPolicy
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

    public function access(User $user, Area $area){
        return true;
    }

    public function create(User $user, Area $area){
        return true;
    }

    public function update(User $user, Area $area){
        return true;
    }

    public function edit(User $user, Area $area){
        return true;
    }

    public function view(User $user, Area $area){
        return true;
    }

    public function delete(User $user, Area $area){
        return true;
    }
}
