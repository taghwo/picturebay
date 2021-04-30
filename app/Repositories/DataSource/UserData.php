<?php

namespace App\Repositories\DataSource;

use App\Models\User;
use App\Repositories\Contracts\UserContract;
use App\Repositories\RepositoryAbstract;

class UserData extends RepositoryAbstract implements UserContract
{
    public function entity()
    {
        return User::class;
    }
}
