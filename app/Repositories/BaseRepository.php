<?php

namespace App\Repositories;

abstract class BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->makeModel();
    }

    public function makeModel()
    {
        $this->model = app()->make($this->model());
    }

    abstract public function model();

    public function all()
    {
        return $this->model->all();
    }

    public function create($dataInsert)
    {
        return $this->model->create($dataInsert);
    }
}
