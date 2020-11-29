<?php

namespace App\Providers;

use App\models\acl\AclList;
use App\models\acl\AclUserRelations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('userAclGate', function($user, $role){
            if($user->level == getValueInfo('superAdminLevel'))
                return true;

            $acl = AclList::where('actualName', $role)->first();
            if($acl != null){
                $uAcl = AclUserRelations::where('userId', $user->id)->where('aclId', $acl->id)->first();
                return $uAcl != null;
            }
            else
                return false;
        });

        Gate::define('superAdminAccess', function($user){
            return $user->level == getValueInfo('superAdminLevel');
        });
    }
}
