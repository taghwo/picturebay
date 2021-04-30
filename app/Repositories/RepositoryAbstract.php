<?php
namespace App\Repositories;

use App\Exceptions\ModelNotFoundException;
use App\Repositories\Contracts\BaseContract;
use App\Repositories\Exceptions\NoEntityDefined;

abstract class RepositoryAbstract implements BaseContract
{
    use RepositoryTraits;

    protected $entity;

    public function __construct()
    {
        $this->entity = $this->resolveEntity();
    }

    public function createBulk($array)
    {
        return $this->entity->insert(
            $array
        );
    }

    public function create(array $data)
    {
        return $this->entity->create($data);
    }

    public function all()
    {
        return $this->entity->orderBy('id', 'DESC')->get();
    }

    public function paginateResult($page=10, $orderBy = 'id', $direction = 'DESC')
    {
        return $this->entity->orderBy($orderBy, $direction)->paginate($page);
    }


    public function find($id)
    {
        $data =  $this->entity->find($id);

        return $this->signleModelQuery($data);

    }

    public function findUUID($uuid)
    {
        $data =  $this->entity->where('uuid', $uuid)->first();

       return $this->signleModelQuery($data);
    }


    private function signleModelQuery($data){
        if (is_null($data)) {
            $dirtyClass = $this->entity instanceof \illuminate\database\eloquent\builder ? $this->entity->getModel() : $this->entity;

            throw new ModelNotFoundException(sprintf("no record for that %s was found", strtolower(str_replace('App\Models\\', '', get_class($dirtyClass)))));
        }
        return $data;
    }

    public function update($id, $properties)
    {
        $data = $this->find($id);
        $data->update($properties);
        return $data;
    }


    public function findWhere($column, $value)
    {
        return $this->entity->where($column, $value)->latest()->get();
    }

    public function findWherePaginate($column, $value, $page = 10, $orderBy = 'id', $direction = 'DESC')
    {
        return $this->entity->where($column, $value)->orderBy($orderBy, $direction)->paginate($page);
    }

    public function newOrExisting($first_array, $second_array)
    {
        return $this->entity->updateOrCreate($first_array, $second_array);
    }

    public function setPivot($relationship, $action, $values)
    {
        return $relationship->{$action}($values);
    }

    /**
     * find where model exists first
     * @param string $column
     * @param string $value
     * @return collection
     */
    public function findFirstWhere(array $columns)
    {
        return $this->entity->where($columns)->first();
    }


    /**
     * find where model exists first
     * @param array $whereColumns
     * @param array $orWhereColumns
     * @return collection
     */
    public function findOrWhere(array $whereColumns, array $orWhereColumns):object
    {
        return $this->entity->where($whereColumns)->OrWhere($orWhereColumns)->get();
    }


    public function delete($id)
    {
        $data = $this->find($id);
        $data->delete();
        return $data;
    }

    protected function resolveEntity()
    {
        if (!method_exists($this, 'entity')) {
            throw new NoEntityDefined();
        }
        return app()->make($this->entity());
    }
}
