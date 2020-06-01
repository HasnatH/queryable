<?php

namespace HasnatH\Queryable;

use HasnatH\Queryable\Traits\Filter;
use HasnatH\Queryable\Traits\OrderBy;
use Illuminate\Support\Facades\Schema;

class Queryable
{
    use Filter, OrderBy;

    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getTable()
    {
        return (new $this->model)->getTable();
    }

    public function getFields()
    {
        return Schema::getColumnListing($this->getTable());
    }

    public function getFillable($collection)
    {
        return $collection->only(
            with((new $this->model)->getFillable())
        );
    }
}