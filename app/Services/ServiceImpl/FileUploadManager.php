<?php
namespace App\Services\ServiceImpl;

use App\Services\Contracts\FileUploadManagerContract;
use Illuminate\Database\Eloquent\Model;

class FileUploadManager implements FileUploadManagerContract
{
    protected Model $model;
    protected string $service;

    protected array $uploadService = [
        'product_images' => ProductImageUpload::class,
        'photoshoot_images' => PhotoShootImageUpload::class
    ];

    public function put(Model $model, array $files, string $service)
    {
        return (new $this->uploadService[$service]($model))->handler($files);
    }
}
