<?php

namespace App\Repositories\DataSource;

use App\Models\Product;
use App\Repositories\Contracts\ProductContract;
use App\Repositories\RepositoryAbstract;

class ProductData extends RepositoryAbstract implements ProductContract
{
    public function entity()
    {
        return Product::class;
    }
}
