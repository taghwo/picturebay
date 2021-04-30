<?php

namespace App\Repositories\DataSource;

use App\Models\PhotographerRequest;
use App\Repositories\Contracts\PhotographerRequestContract;
use App\Repositories\RepositoryAbstract;

class PhotographerRequestData extends RepositoryAbstract implements PhotographerRequestContract
{
    public function entity()
    {
        return PhotographerRequest::class;
    }
}
