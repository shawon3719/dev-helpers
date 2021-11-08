<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class LeavePolicy
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

    public function view($user, $ability)
    {
        return true;
    }

    public function edit($user, $ability)
    {
        $authoriry = explode(',',$ability->mail_cc);
        $authoriry[] = $ability->mail_to;

        if (isSuperAdmin() === true || in_array($user->id, $authoriry)){
            return true;
        }

        // edit will be allowed till it is in draft(Pending) status
        return (string)$ability->status === "draft";
    }

    public function delete($user, $ability)
    {
        $authoriry = explode(',',$ability->mail_cc);
        $authoriry[] = $ability->mail_to;

        if (isSuperAdmin() === true || in_array($user->id, $authoriry)){
            return true;
        }

        // delete will be allowed till it is in draft(Pending) status
        return (string)$ability->status === "draft";
    }

    public function approve($user, $ability)
    {
        $authoriry = explode(',',$ability->mail_cc);
        $authoriry[] = $ability->mail_to;
        
        if (isSuperAdmin() === true || in_array($user->id, $authoriry)){
            return true;
        }else{
            return false;
        }
    }

    public function deny($user, $ability)
    {
        $authoriry = explode(',',$ability->mail_cc);
        $authoriry[] = $ability->mail_to;
        
        if (isSuperAdmin() === true || in_array($user->id, $authoriry)){
            return true;
        }else{
            return false;
        }
    }
}
