<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePhotoShootRequest;
use App\Models\PhotoShoot;
use App\Repositories\Contracts\PhotographerRequestContract;
use App\Repositories\Contracts\PhotoShootContract;
use App\Services\Contracts\FileUploadManagerContract;
use Illuminate\Http\Request;

class PhotoShootController extends Controller
{
    protected $photoShoot;
    protected $fileUploadManager;
    protected $photographerRequest;
    public function __construct(PhotoShootContract $photoShoot, FileUploadManagerContract $fileUploadManager, PhotographerRequestContract $photographerRequest)
    {
        $this->photoShoot = $photoShoot;
        $this->fileUploadManager = $fileUploadManager;
        $this->photographerRequest = $photographerRequest;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $requestId = $request->get('request');
        $photoshoot = $this->photoShoot->withModels(['photographerrequest.product'])->findWhere('photographer_request_id', $requestId);
        $this->authorize('view', $photoshoot);
        return $this->respondSuccessWithData($photoshoot);
    }


    /**
     * Store or update photoshoot
     *
     * @param  CreatePhotoShootRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePhotoShootRequest $request)
    {
        $validatedAttr = $request->validated();

        $photoGrapherRequest = $this->photographerRequest->findFirstWhere(['id' => $validatedAttr['photographer_request_id']]);

        $this->authorize('store', [PhotoShoot::class, $photoGrapherRequest]);

        \DB::beginTransaction();
        try {
            $photoShoot = new PhotoShoot();

            $photoShoot->photographer_request_id = $validatedAttr['photographer_request_id'];

            $preparedData  = $this->fileUploadManager->put($photoShoot, $validatedAttr['images'], 'photoshoot_images');

            $photoShoot = $this->photoShoot->createBulk($preparedData, 201);

            \DB::commit();
            return $this->respondSuccessWithData($preparedData);
        } catch (\throwable $e) {
            \DB::rollback();
            return $this->respondErrorWithMessage($e->getMessage());
        }
    }

    /**
     * update the status of a single photo
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updatePhotoStatus($id)
    {
        $photo = $this->photoShoot->withModels(['photographerrequest.product'])->find($id);

        $this->authorize('status', $photo);

        try {
            $updatedPhoto = $this->photoShoot->update($id, ['status' => !$photo->status]);

            return $this->respondSuccessWithData($updatedPhoto);
        } catch (\throwable $e) {
            return $this->respondErrorWithMessage($e->getMessage());
        }
    }

    /**
     * Download HQ file
     *
     * @param  int $request
     * @return \Illuminate\Http\Response
     */
    public function downloadHQFile(int $id)
    {
        $photo = $this->photoShoot->withModels(['photographerrequest.product'])->find($id);

        $this->authorize('download', $photo);

        if (!$photo->status) {
            return $this->respondErrorWithMessage("sorry you have not approved this picture", 400);
        }
        try {
            return response()->download(public_path(getFile($photo->hq_file_path)), $photo->name);
        } catch (\throwable $e) {
            return $this->respondErrorWithMessage($e->getMessage());
        }
    }

    /**
     * Show photo
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $photo = $this->photoShoot->withModels(['photographerrequest.product'])->find($id);
        $this->authorize('view', $photo);
        return $this->respondSuccessWithData($photo);
    }

    /**
     *Delete photo
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $photo = $this->photoShoot->withModels(['photographerrequest.product'])->find($id);
        $this->authorize('delete', $photo);

        try {
            $this->photoShoot->delete($id);
            return $this->respondWithSuccess('photo deleted');
        } catch (\Throwable $th) {
            return $this->respondErrorWithMessage($th->getMessage());
        }
    }
}
