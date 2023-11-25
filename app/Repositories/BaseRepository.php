<?php


namespace App\Repositories;

use Exception;

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
}
