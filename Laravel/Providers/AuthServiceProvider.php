<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Zone' => 'App\Policies\ZonePolicy',
        'App\Region' => 'App\Policies\RegionPolicy',
        'App\Area' => 'App\Policies\AreaPolicy',
        'App\Territory' => 'App\Policies\TerritoryPolicy',
        'App\Leave' => 'App\Policies\LeavePolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
    }
}
