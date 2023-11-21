<?php


namespace App\Repositories;

use Exception;

abstract class BaseRepository
{
    protected $model;

    abstract function getAll();

    abstract function getById($uuid, $relations = []);

    abstract function store(array $data);
}
