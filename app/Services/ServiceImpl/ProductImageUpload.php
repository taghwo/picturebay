<?php
namespace App\Services\ServiceImpl;

class ProductImageUpload extends BaseUploadService
{
    protected const PARENT_DIR = "product_images";

    protected array $payload;

    public function handler(array $files):array
    {
        foreach ($files as $file) {
            $this->ext =  $file->getClientOriginalExtension();
            $this->payload[] =  ['file_path' => $file->storeAs(SELF::PARENT_DIR, $this->filename($this->model->name), 'public'),'product_id' => $this->model->id];
        }

        return $this->payload;
    }
}
