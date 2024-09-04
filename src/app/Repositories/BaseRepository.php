<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * Model name
     *
     * @var string
     */
    protected $modelName;

    /**
     * Current object instance
     *
     * @var object
     */
    protected $instance;

    /**
     * Order Options
     *
     * @var array
     */
    protected $orderOptions = [];

    /**
     * Default order by
     *
     * @var string
     */
    private $orderBy = 'id';

    /**
     * Default order direction
     *
     * @var string
     */
    private $orderDirection = 'asc';

    /**
     * Return all items
     *
     * @param string $orderBy
     * @return mixed
     */
    public function all($orderBy = 'id', array $relations = [], array $parameters = [])
    {
        $instance = $this->getQueryBuilder();

        $this->parseOrder($orderBy);

        $this->applyFilters($instance, $parameters);

        return $instance->with($relations)
            ->orderBy($this->getOrderBy(), $this->getOrderDirection())
            ->get();
    }

    /**
     * Paginate items
     *
     * @param string $orderBy
     * @param int $paginate
     * @return mixed
     */
    public function paginate($orderBy = 'name', array $relations = [], $paginate = 50, array $parameters = [])
    {
        $instance = $this->getQueryBuilder();

        $this->parseOrder($orderBy);

        $this->applyFilters($instance, $parameters);

        return $instance->with($relations)
            ->orderBy($this->getOrderBy(), $this->getOrderDirection())
            ->paginate($paginate);
    }

    /**
     * Apply parameters, which can be extended in child classes for filtering
     */
    protected function applyFilters(Builder $instance, array $filters = []): void
    {
        foreach ($filters as $filterName => $filterValue) {
            if (in_array($filterName, $this->filterable) && ! empty($filterValue)) {
                $instance->where($filterName, '=', $filterValue);
            }
        }
    }

    /**
     * Get all items by a field
     *
     * @return mixed
     */
    public function getBy(array $parameters, array $relations = [])
    {
        $instance = $this->getQueryBuilder()
            ->with($relations);

        foreach ($parameters as $field => $value) {
            $instance->where($field, $value);
        }

        return $instance->get();
    }

    /**
     * List all items
     *
     * @param string $fieldName
     * @param string $fieldId
     * @return mixed
     */
    public function pluck($fieldName = 'name', $fieldId = 'id')
    {
        $instance = $this->getQueryBuilder();

        return $instance
            ->orderBy($fieldName)
            ->pluck($fieldName, $fieldId)
            ->all();
    }

    /**
     * List records limited by a certain field
     *
     * @param string $field
     * @param string|array $value
     * @param string $listFiledName
     * @param string $listFieldId
     * @return mixed
     */
    public function pluckBy($field, $value, $listFiledName = 'name', $listFieldId = 'id')
    {
        $instance = $this->getQueryBuilder();

        if (! is_array($value)) {
            $value = [$value];
        }

        return $instance
            ->WhereIn($field, $value)
            ->orderBy($listFiledName)
            ->pluck($listFiledName, $listFieldId)
            ->all();
    }

    /**
     * Find a single item
     *
     * @param int|string $id
     * @return mixed
     */
    public function find($id, array $cols = ['*'], array $relations = [])
    {
        $this->instance = $this->getQueryBuilder()->with($relations)->findOrFail($id, $cols);

        return $this->instance;
    }

    /**
     * Find a single record y a field and value
     *
     * @param string $field
     * @param string $value
     * @return mixed
     */
    public function findBy($field, $value, array $relations = [])
    {
        $this->instance = $this->getQueryBuilder()->with($relations)->where($field, $value)->first();

        return $this->instance;
    }

    /**
     * Find a single record by multiple fields
     *
     * @return mixed
     */
    public function findByMany(array $data, array $relations = [])
    {
        $model = $this->getQueryBuilder()->with($relations);

        foreach ($data as $key => $value) {
            $model->where($key, $value);
        }

        $this->instance = $model->first();

        return $this->instance;
    }

    /**
     * Find multiple records
     *
     * @return object
     */
    public function getWhereIn(array $ids, array $relations = [])
    {
        $this->instance = $this->getQueryBuilder()->with($relations)->whereIn('id', $ids)->get();

        return $this->instance;
    }

    /**
     * Store a newly created item
     *
     * @return mixed
     */
    public function store(array $data)
    {
        $this->instance = $this->getNewInstance();

        return $this->executeSave($data);
    }

    /**
     * Update an existing item
     *
     * @param int|string $id
     * @return mixed
     */
    public function update($id, array $data)
    {
        $this->instance = $this->find($id);

        return $this->executeSave($data);
    }

    /**
     * Save the model
     *
     * @return mixed
     */
    protected function executeSave(array $data)
    {
        $this->instance->fill($data);
        $this->instance->save();

        return $this->instance;
    }

    /**
     * Remove an item from storage
     *
     * @param int|string $id
     * @return mixed
     */
    public function destroy($id)
    {
        $instance = $this->find($id);

        return $instance->delete();
    }

    /**
     * Get count of records
     *
     * @return int
     */
    public function count()
    {
        return $this->getNewInstance()->count();
    }

    /**
     * Return model name
     *
     * @return string
     *
     * @throws \Exception If model has not been set
     */
    public function getModelName()
    {
        if (! $this->modelName) {
            throw new \Exception('Model has not been set in ' . get_called_class());
        }

        return $this->modelName;
    }

    /**
     * Return a new query builder instance
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getQueryBuilder(): Builder
    {
        return $this->getNewInstance()->newQuery();
    }

    /**
     * Return new model instance
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getNewInstance()
    {
        $model = $this->getModelName();

        return new $model;
    }

    /**
     * Resolve order by
     *
     * @param string $orderBy
     * @return void
     */
    protected function resolveOrder($orderBy)
    {
        if (! request('sort_by')) {
            $this->parseOrder($orderBy);

            return;
        }

        $this->resolveDirection();
        $this->resolveOrderBy($orderBy);
    }

    /**
     * Resolve direction
     *
     * @return void
     */
    protected function resolveDirection()
    {
        $direction = strtolower(request('direction', 'asc'));

        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $this->setOrderDirection($direction);
    }

    /**
     * Resolve order by
     *
     * @param string $column
     * @return void
     */
    protected function resolveOrderBy($column)
    {
        $orderBy = request('sort_by');

        $orderBy = Arr::get($this->orderOptions, $orderBy, $column);

        $this->setOrderBy($orderBy);
    }

    /**
     * Parse the order by data
     *
     * @param string $orderBy
     * @return void
     */
    protected function parseOrder($orderBy)
    {
        if (substr($orderBy, -3) == 'Asc') {
            $this->setOrderDirection('asc');
            $orderBy = substr_replace($orderBy, '', -3);
        } elseif (substr($orderBy, -4) == 'Desc') {
            $this->setOrderDirection('desc');
            $orderBy = substr_replace($orderBy, '', -4);
        }

        $this->setOrderBy($orderBy);
    }

    /**
     * Set the order by field
     *
     * @param string $orderBy
     * @return void
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * Get the order by field
     *
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Set the order direction field
     *
     * @param string $orderDirection
     * @return void
     */
    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;
    }

    /**
     * Get the order direction field
     *
     * @return string
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }
}
