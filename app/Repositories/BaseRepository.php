<?php


namespace App\Repositories;

use App\Exceptions\CustomExceptionHandler;

abstract class BaseRepository
{
    protected $model;

    abstract function getAll();

    /**
     * getOneByCondition
     *
     * @param  mixed $condition=['key' => 'value of key', 'value' => 'value of value']
     * @param  mixed $relations
     * @return void
     */
    abstract function getOneByCondition($condition, $relations = []);

    abstract function store(array $data);

    public function getById($uuid, $relations = [])
    {
        try {
            $data = $this->model->with($relations)->find($uuid);
            if (!$data) throw new CustomExceptionHandler("Data obat tidak ditemukan!", 404);
            return $data;
        } catch (CustomExceptionHandler $e) {
            throw $e;
        }
    }

    public function checkExistingDataWithTrashed($key, $value)
    {
        return $this->model->withTrashed()->where($key, $value)->first();
    }
}
