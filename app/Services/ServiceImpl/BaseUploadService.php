<?php
namespace App\Services\ServiceImpl;

use App\Services\Contracts\BaseUploadContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class BaseUploadService implements BaseUploadContract
{
    protected Model $model;

    protected array $files;

    protected string $ext;

    public function __construct(Model $model)
    {
        $this->model =  $model;
    }


    public function filename(string $file_name):string
    {
        return substr(Str::slug($file_name), 0, 50).'-'.time(). '-'.rand(1, 100).'.'.$this->ext;
    }
}
