<?php

namespace App\Providers;

use App\Repositories\Contracts\ActivityContract;
use App\Repositories\Contracts\ActivityLogContract;
use App\Repositories\Contracts\CategoryContract;
use App\Repositories\Contracts\FacilitatorContract;
use App\Repositories\Contracts\FolderContract;
use App\Repositories\Contracts\PhotographerRequestContract;
use App\Repositories\Contracts\PhotoShootContract;
use App\Repositories\Contracts\ProductContract;
use App\Repositories\Contracts\ProductImageContract;
use App\Repositories\Contracts\ProjectContract;
use App\Repositories\Contracts\ProjectFileContract;
use App\Repositories\Contracts\ProjectFundContract;
use App\Repositories\Contracts\ProjectFundFilesContract;
use App\Repositories\Contracts\SettingContract;
use App\Repositories\Contracts\TeamContract;
use App\Repositories\Contracts\TeamUserContract;
use App\Repositories\Contracts\TransactionContract;
use App\Repositories\Contracts\UserContract;
use App\Repositories\DataSource\ActivityData;
use App\Repositories\DataSource\ActivityLogData;
use App\Repositories\DataSource\CategoryData;
use App\Repositories\DataSource\FacilitatorData;
use App\Repositories\DataSource\FolderData;
use App\Repositories\DataSource\PhotographerRequestData;
use App\Repositories\DataSource\PhotoShootData;
use App\Repositories\DataSource\ProductData;
use App\Repositories\DataSource\ProductImageData;
use App\Repositories\DataSource\UserData;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind(ProductContract::class, ProductData::class);
        $this->app->bind(ProductImageContract::class, ProductImageData::class);
        $this->app->bind(UserContract::class, UserData::class);
        $this->app->bind(PhotographerRequestContract::class, PhotographerRequestData::class);
        $this->app->bind(PhotoShootContract::class, PhotoShootData::class);
    }
}
