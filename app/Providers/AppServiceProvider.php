<?php

namespace App\Providers;

use App\Repositories\DataSource\RoleDataSource;
use App\Repositories\DataSource\UserData;
use App\Services\Contracts\AuthServiceContract;
use App\Services\Contracts\FileUploadManagerContract;
use App\Services\ServiceImpl\AuthService;
use App\Services\ServiceImpl\FileUploadManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind(AuthServiceContract::class, fn () => new AuthService(new UserData, new RoleDataSource));
        app()->bind(FileUploadManagerContract::class, FileUploadManager::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
