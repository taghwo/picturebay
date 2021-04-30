<?php

namespace App\Repositories\DataSource;

use App\Models\PhotoShoot;
use App\Repositories\Contracts\PhotoShootContract;
use App\Repositories\RepositoryAbstract;

class PhotoShootData extends RepositoryAbstract implements PhotoShootContract
{
    public function entity()
    {
        return PhotoShoot::class;
    }
}
