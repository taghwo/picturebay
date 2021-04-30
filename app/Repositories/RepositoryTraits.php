<?php
namespace App\Repositories;

trait RepositoryTraits
{
    /**
    * fetch related models with $this->entity,
    * pass single model or multiple models to eagerloaded
    * ['firstmodel','secondmodel'], can also select fields ['firstmodel:name,age','secondmodel:id,age']
    * @param array $models
    * @return $this
    */
    public function withModels(array $models)
    {
        $this->entity = $this->entity->with($models);

        return $this;
    }


}
