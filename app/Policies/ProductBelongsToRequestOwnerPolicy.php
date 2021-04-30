<?php

namespace App\Policies;

use App\Exceptions\GeneralException;
use App\Exceptions\UnauthorisedException;
use App\Models\PhotographerRequest;
use App\Models\User;
use App\Repositories\Contracts\ProductContract;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProductBelongsToRequestOwnerPolicy
{
    use HandlesAuthorization;

    protected ProductContract $product;

    public function __construct()
    {
        $this->product = app(ProductContract::class);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PhotographerRequest  $photographerRequest
     * @return mixed
     */
    public function view(User $user, PhotographerRequest $photographerRequest)
    {
        if ($user->id === (int)$photographerRequest->photographer_id || $user->id === (int)$photographerRequest->product->user_id) {
            return Response::allow();
        };
        throw new UnauthorisedException("Sorry you dont have access to view this request");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PhotographerRequest  $photographerRequest
     * @return mixed
     */
    public function createOrUpdate(User $user, $data)
    {
        if (!$this->product->findFirstWhere(['id' => $data['product_id'], 'user_id' => $user->id])) {
            throw new UnauthorisedException("Sorry you cannot assign a product you did not create");
        };
        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PhotographerRequest  $photographerRequest
     * @return mixed
     */
    public function delete(User $user, PhotographerRequest $photographerRequest)
    {
        if ($user->id === (int)$photographerRequest->photographer_id || $user->id === (int)$photographerRequest->product->user_id) {
            return  Response::allow();
        };
        throw new UnauthorisedException("Sorry you dont have access to delete this request");
    }

}
