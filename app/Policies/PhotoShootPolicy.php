<?php

namespace App\Policies;

use App\Exceptions\UnauthorisedException;
use App\Models\PhotoShoot;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PhotoShootPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PhotoShoot  $photoShoot
     * @return mixed
     */
    public function view(User $user, PhotoShoot $photoShoot)
    {
        if ($user->id === $photoShoot->photographerrequest->photographer_id || $user->id === $photoShoot->photographerrequest->product->user_id) {
            return Response::allow();
        }
        throw new UnauthorisedException('Sorry you don\'t have access to view this photoshoot collection');
    }
    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function store(User $user,$photographerrequest)
    {
        if($user->id === $photographerrequest->photographer_id){
        return Response::allow();
        }
        throw new UnauthorisedException('Sorry you don\'t have access to create a photoshoot collection for a request that was not assigned to you');

    }

    /**
     * Determine whether the user can downnload photo
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PhotoShoot  $photoShoot
     * @return mixed
     */
    public function download(User $user, PhotoShoot $photoShoot)
    {
        if($user->id === $photoShoot->photographerrequest->photographer_id || $user->id === $photoShoot->photographerrequest->product->user_id){
            return Response::allow();
        }
         throw new UnauthorisedException('Sorry you don\t have access to download this photo');
    }
    /**
     * Determine whether the user can change status of photo.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PhotoShoot  $photoShoot
     * @return mixed
     */
    public function status(User $user, PhotoShoot $photoShoot)
    {
        if($user->id !== $photoShoot->photographerrequest->product->user_id){
            throw new UnauthorisedException('Sorry only the request owner can update the status of this photo');
        }
        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PhotoShoot  $photoShoot
     * @return mixed
     */
    public function delete(User $user, PhotoShoot $photoShoot)
    {
        if($user->id !== $photoShoot->photographerrequest->photographer_id){
            throw new UnauthorisedException('Sorry you don\'t have access to delete this photoshoot collection');
        }
        return Response::allow();
    }

}
