<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\Contracts\ProductContract;
use App\Repositories\Contracts\ProductImageContract;
use App\Services\Contracts\FileUploadManagerContract;

class ProductController extends Controller
{
    protected $product;
    protected $fileUploadManager;
    protected $productImages;
    public function __construct(ProductContract $product, FileUploadManagerContract $fileUploadManager, ProductImageContract $productImages)
    {
        $this->product = $product;
        $this->fileUploadManager = $fileUploadManager;
        $this->productImages = $productImages;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respondWithPaginatedData(
            $this->product
                 ->withModels(['images','photographrequest.photographer:id,full_name'])->findWherePaginate('user_id', auth()->id())
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $validatedAttr = $request->validated();

        $validatedAttr['user_id'] = auth()->id();

        \DB::beginTransaction();
        try {
            $product = $this->product->create($validatedAttr);
            isset($validatedAttr['images'])?$this->saveFilesToDatabase($this->fileUploadManager->put($product, $validatedAttr['images'], 'product_images')):'';
            \DB::commit();
            return $this->respondSuccessWithData($product,201);
        } catch (\Throwable $e) {
            \DB::rollback();
            return $this->respondErrorWithMessage($e->getMessage());
        }
    }

    private function saveFilesToDatabase(array $payload):void
    {
        $this->productImages->createBulk($payload);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show(string $uuid)
    {
        if (!$product = $this->product
                            ->withModels(['images','photographrequest.photographer:id,full_name'])
                            ->findFirstWhere(['uuid' => $uuid,'user_id' => auth()->id()])) {
            return $this->respondModelNotFoundError('Product');
        }

        return $this->respondSuccessWithData($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProductRequest  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, string $uuid)
    {
        $validatedAttr = $request->validated();

        if (!$product = $this->product->findFirstWhere(['uuid' => $uuid,'user_id' => auth()->id()])) {
            $this->respondModelNotFoundError('Product');
        }

        \DB::beginTransaction();
        try {
            $updatedProduct = $this->product->update($product->id, $validatedAttr);
            isset($validatedAttr['images'])? $this->saveFilesToDatabase($this->fileUploadManager->put($updatedProduct, $validatedAttr['images'], 'product_images')):'';
            \DB::commit();
            return $this->respondSuccessWithData($updatedProduct);
        } catch (\Throwable $e) {
            \DB::rollback();
            return $this->respondErrorWithMessage($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $uuid)
    {
        if (!$product = $this->product->findFirstWhere(['uuid' => $uuid,'user_id' => auth()->id()])) {
            return  $this->respondModelNotFoundError('Product');
        }
        try {
            $this->product->delete($product->id);
            return $this->respondSuccess('Successfully deleted product');
        } catch (\Exception $e) {
            return $this->respondErrorWithMessage($e->getMessage());
        };
    }
}
