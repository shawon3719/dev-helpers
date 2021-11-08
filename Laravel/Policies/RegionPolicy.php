<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Region;

class RegionPolicy
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

    public function access(User $user, Region $region){
        return true;
    }

    public function create(User $user, Region $region){
        return true;
    }

    public function update(User $user, Region $region){
        return true;
    }

    public function edit(User $user, Region $region){
        return true;
    }

    public function view(User $user, Region $region){
        return true;
    }

    public function delete(User $user, Region $region){
        return true;
    }
}
