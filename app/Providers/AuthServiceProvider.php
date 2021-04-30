<?php

namespace App\Providers;

use App\Models\PhotographerRequest;
use App\Models\PhotoShoot;
use App\Policies\PhotoShootPolicy;
use App\Policies\ProductBelongsToRequestOwnerPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         PhotographerRequest::class => ProductBelongsToRequestOwnerPolicy::class,
         PhotoShoot::class => PhotoShootPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
