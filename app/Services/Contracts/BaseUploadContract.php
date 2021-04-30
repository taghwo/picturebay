<?php
namespace App\Services\Contracts;

interface BaseUploadContract
{
    public function filename(string $file_name);
    public function handler(array $files):array;
}
