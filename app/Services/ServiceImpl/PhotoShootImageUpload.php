<?php
namespace App\Services\ServiceImpl;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PhotoShootImageUpload extends BaseUploadService
{
    protected const PARENT_DIR = "photoshoot_images";
    protected const THUMBNAIL_DIR = "photoshoot_thumbnail_images";

    protected array $payload;

    public function handler(array $files):array
    {
        foreach ($files as $file) {
            $this->setFileExtension($file);
            $hqImage = $file->storeAs(SELF::PARENT_DIR, $this->filename($this->getName($this->ext,$file)), 'public');
            $thumbnail = $file->storeAs(SELF::THUMBNAIL_DIR, $this->filename($this->getName($this->ext,$file)), 'public');
            $this->resizeImage($thumbnail);
            $this->payload[] =  [
                                  'hq_file_path' => $hqImage,
                                  'thumbnail_file_path' => $thumbnail, 
                                  'photographer_request_id' => $this->model->photographer_request_id,
                                  'name' => $this->getCleanName($this->ext,$file),
                                  'created_at' => now()
                                ];
        }

        return $this->payload;
    }

    private function setFileExtension(UploadedFile $file):void{
         $this->ext =  $file->getClientOriginalExtension();
    }
    private function resizeImage(string $thumbnail):void{
        $thumbnailPath = public_path("storage/".$thumbnail);
        Image::make($thumbnailPath)->resize(200, 200)->save($thumbnailPath);
    }
    private function getName(string $ext, UploadedFile $file):string{
        $filenameArray = explode($ext, $file->getClientOriginalName());
        return substr($filenameArray[0],0,20).'-'.Str::uuid();
    }

    private function getCleanName(string $ext, UploadedFile $file):string{
        $filenameArray = explode($ext, $file->getClientOriginalName());
        return $filenameArray[0].'-'.rand(1,1000).'.'.$ext;
    }

}
