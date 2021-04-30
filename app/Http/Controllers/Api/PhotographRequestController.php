<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePhotographerRequest;
use App\Models\PhotographerRequest;
use App\Repositories\Contracts\PhotographerRequestContract;
use App\Repositories\Contracts\ProductContract;
use App\Repositories\Contracts\UserContract;

class PhotographRequestController extends Controller
{
    protected $product;
    protected $user;
    protected $photographerRequest;

    public function __construct(ProductContract $product, UserContract $user, PhotographerRequestContract $photographerRequest)
    {
        $this->product = $product;
        $this->photographerRequest = $photographerRequest;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respondWithPaginatedData($this->product->withModels(['images'])->paginateResult());
    }


     /**
     * Fetch photographer request belonging to auth user
     *
     * @return \Illuminate\Http\Response
     */
    public function myrequest()
    {
        return $this->respondWithPaginatedData(
               $this->photographerRequest->withModels(['product.images'])->findWherePaginate('photographer_id',auth()->id())
                                              );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePhotographerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePhotographerRequest $request)
    {
        $validatedAttr = $request->validated();

        $this->authorize('createOrUpdate', [PhotographerRequest::class, $validatedAttr]);

        try {
            $photographerRequest = $this->photographerRequest->newOrExisting($validatedAttr, $validatedAttr);

            $this->product->update($validatedAttr['product_id'], ['status' => 'assigned']);

            return $this->respondSuccessWithData($photographerRequest);
        } catch (\Throwable $e) {
            return $this->respondErrorWithMessage($e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        if (!$photographerRequest = $this->photographerRequest
                            ->withModels(['photographer:id,full_name,email','product.images'])
                            ->find($id)) {
            $this->respondModelNotFoundError('Photographer Request');
        }

        $this->authorize('view', $photographerRequest);

        return $this->respondSuccessWithData($photographerRequest);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        if (!$photographerRequest = $this->photographerRequest->find($id)) {
            $this->respondModelNotFoundError('Photographer Request');
        }

        $this->authorize('view', $photographerRequest);

        try {
            $this->photographerRequest->delete($photographerRequest->id);
            return $this->respondWithSuccess('Photographer Request deleted successfully');
        } catch (\Exception $e) {
            return $this->respondErrorWithMessage($e->getMessage());
        };
    }
}
