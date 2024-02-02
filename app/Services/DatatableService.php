<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;

class DatatableService
{
    public static function getDataForTable(
        Model $model,
        $perPage,
        $search,
        $sortField,
        $sortDirection,
        $distinctColumn = null,
        $relation = [],
        $optionals = null
    ) {
        try {
            $query = $model->query();
            $query = $query->search($search, null, true, true);

            if ($sortField && $sortDirection) {
                $query = $query->orderBy($sortField, $sortDirection);
            }
            if (!is_null($distinctColumn)) {
                $query = $query->distinct($distinctColumn);
            }

            if (!is_null($optionals)) {
                foreach ($optionals as $optional) {
                    $query = $query->where($optional['field'], $optional['operator'], $optional['value']);
                }
            }
            $data = $query->with($relation)->paginate($perPage);

            return $data;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function getDataWithConditional(
        Model $model,
        $perPage,
        $search,
        $sortField,
        $sortDirection,
        $distinctColumn = null,
        $relation = [],
        $coditional,
        $optionals = null
    ) {
        try {
            $query = $model->query();
            $query = $query->search($search, null, true, true);

            if ($sortField && $sortDirection) {
                $query = $query->orderBy($sortField, $sortDirection);
            }
            if (!is_null($distinctColumn)) {
                $query = $query->distinct($distinctColumn);
            }

            $query = $query->where($coditional['field'], $coditional['value']);

            if (!is_null($optionals)) {
                foreach ($optionals as $optional) {
                    $query = $query->where($optional['field'], $optional['operator'], $optional['value']);
                }
            }

            $data = $query->with($relation)->paginate($perPage);

            return $data;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
