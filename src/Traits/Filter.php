<?php

namespace HasnatH\Queryable\Traits;

trait Filter
{
    public function filter($query, $filters)
    {
        if ( ! $filters || ! count($filters)) {
            return $query;
        }

        foreach ($this->getFilters($filters) as $filter) {
            $query->where($filter['field'], $filter['operator'], $filter['value']);
        }

        return $query;
    }

    public function getFilters($filterByFields)
    {
        $filters = [];

        if ( ! $filterByFields || ! count($filterByFields)) {
            return $filters;
        }

        $fields = $this->getFilterable();

        foreach ($filterByFields as $filter) {
            $filter = explode(config('queryable.filter.separator'), $filter);

            if (in_array($filter[0], $fields)) {
                $filters[] = [
                    'field'    => $filter[0],
                    'value'    => $filter[1],
                    'operator' => count($filter) == 3 ? $filter[2] : '='
                ];
            }
        }

        return $filters;
    }

    public function getFilterable()
    {
        $model = $this->getModel();

        return property_exists($model, 'filterable')
            ? $model::$filterable
            : $this->getFields();
    }
}