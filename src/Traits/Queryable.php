<?php

namespace HasnatH\Queryable\Traits;

use Illuminate\Support\Facades\Schema;

trait Queryable
{
    use Filter, OrderBy;

    protected $model;

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getFields()
    {
        return Schema::getColumnListing((new $this->model)->getTable());
    }

    public function getFillable($collection)
    {
        return $collection->only(
            with((new $this->model)->getFillable())
        );
    }
}