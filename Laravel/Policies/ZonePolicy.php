<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Zone;

class ZonePolicy
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

    public function access(User $user, Zone $zone){
        return true;
    }

    public function create(User $user, Zone $zone){
        return true;
    }

    public function update(User $user, Zone $zone){
        return true;
    }

    public function edit(User $user, Zone $zone){
        return true;
    }

    public function view(User $user, Zone $zone){
        return true;
    }

    public function delete(User $user, Zone $zone){
        return true;
    }
}
