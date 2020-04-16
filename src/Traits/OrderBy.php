<?php

namespace HasnatH\Queryable\Traits;

trait OrderBy
{
    public function orderBy($query, $orderByFields)
    {
        if ( ! $orderByFields || ! count($orderByFields)) {
            return $query;
        }

        foreach ($this->getOrderBy($orderByFields) as $orderBy) {
            $query->orderBy($orderBy['field'], $orderBy['order']);
        }

        return $query;
    }

    public function getOrderBy($orderByFields)
    {
        $orderBy = [];

        if ( ! $orderByFields || ! count($orderByFields)) {
            return $orderBy;
        }

        $fields = $this->getOrderable();

        foreach ($orderByFields as $orderByField) {
            $orderByField = explode(config('queryable.orderBy.separator'), $orderByField);

            if (in_array($orderByField[0], $fields)) {
                $orderBy[] = [
                    'field' => $orderByField[0],
                    'order' => count($orderByField) == 2 ? $orderByField[1] : 'ASC'
                ];
            }
        }

        return $orderBy;
    }

    public function getOrderable()
    {
        $model = $this->getModel();

        return property_exists($model, 'orderable')
            ? $model::$orderable
            : $this->getFields();
    }
}