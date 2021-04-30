<?php

namespace App\Repositories\DataSource;

use App\Models\ProductImage;
use App\Repositories\Contracts\ProductImageContract;
use App\Repositories\RepositoryAbstract;

class ProductImageData extends RepositoryAbstract implements ProductImageContract
{
    public function entity()
    {
        return ProductImage::class;
    }
}
