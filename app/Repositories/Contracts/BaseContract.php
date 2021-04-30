<?php

namespace App\Repositories\Contracts;

interface BaseContract
{
    public function createBulk($array);

    public function create(array $data);

    public function all();

    public function paginateResult($page, $orderBy, $direction);

    public function find($id);

    public function update($id, $properties);

    public function findWhere($column, $value);

    public function findWherePaginate($column, $value, $page, $orderBy, $direction);

    public function newOrExisting($first_array, $second_array);

    public function setPivot($relationship, $action, $values);

    public function findFirstWhere(array $columns);

    public function delete($id);

}
