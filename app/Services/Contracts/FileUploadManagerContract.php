<?php
namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Model;

interface FileUploadManagerContract
{
    public function put(Model $model, array $files, string $service);
}
