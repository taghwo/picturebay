<?php

namespace App\Repositories\DataSource;

use App\Models\Role;
use App\Repositories\Contracts\RoleContract;
use App\Repositories\RepositoryAbstract;

class RoleDataSource extends RepositoryAbstract implements RoleContract
{
    public function entity()
    {
        return Role::class;
    }
}
