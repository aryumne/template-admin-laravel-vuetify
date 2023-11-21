<?php


namespace App\Repositories;

use Exception;

abstract class BaseRepository
{
    protected $model;

    abstract function getAll();

    public function getById($uuid, $relations = [])
    {
        return $this->model->where('id', $uuid)->with($relations)->first();
    }

    abstract function store(array $data);
}
