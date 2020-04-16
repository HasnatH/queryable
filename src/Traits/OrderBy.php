<?php

namespace HasnatHoque\Queryable\Traits;

trait OrderBy
{
    protected $orderByFields;

    public function getOrderable()
    {
        $model = $this->getModel();

        return property_exists($model, 'orderable')
            ? $model::$orderable
            : $this->getFields();
    }

    public function getOrderBy($orderByFields)
    {
        $this->orderByFields = [];

        if ( ! $orderByFields) {
            return $this;
        }

        $fields = $this->getOrderable();

        foreach ($orderByFields as $orderBy) {
            $orderBy = explode(config('queryable.orderBy.separator'), $orderBy);

            if (in_array($orderBy[0], $fields)) {
                $this->orderByFields[] = [
                    'field' => $orderBy[0],
                    'order' => count($orderBy) == 2 ? $orderBy[1] : 'ASC'
                ];
            }
        }

        return $this;
    }

    public function applyOrderBy($query)
    {
        if ( ! count($this->orderByFields)) {
            return $query;
        }

        foreach ($this->orderByFields as $orderBy) {
            $query->orderBy($orderBy['field'], $orderBy['order']);
        }

        return $query;
    }
}