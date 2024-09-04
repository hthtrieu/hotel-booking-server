<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    /**
     * Return all items
     *
     * @param string $orderBy
     * @return mixed
     */
    public function all($orderBy = 'id', array $relations = [], array $parameters = []);

    /**
     * Paginate items
     *
     * @param string $orderBy
     * @param int $paginate
     * @return mixed
     */
    public function paginate($orderBy = 'name', array $relations = [], $paginate = 50, array $parameters = []);

    /**
     * Get all items by a field
     *
     * @return mixed
     */
    public function getBy(array $parameters, array $relations = []);

    /**
     * List all items
     *
     * @param string $fieldName
     * @param string $fieldId
     * @return mixed
     */
    public function pluck($fieldName = 'name', $fieldId = 'id');

    /**
     * List records limited by a certain field
     *
     * @param string $field
     * @param string|array $value
     * @param string $listFiledName
     * @param string $listFieldId
     * @return mixed
     */
    public function pluckBy($field, $value, $listFiledName = 'name', $listFieldId = 'id');

    /**
     * Find a single item
     *
     * @param int|string $id
     * @return mixed
     */
    public function find($id, array $cols = ['*'], array $relations = []);

    /**
     * Find a single record y a field and value
     *
     * @param string $field
     * @param string $value
     * @return mixed
     */
    public function findBy($field, $value, array $relations = []);

    /**
     * Find a single record by multiple fields
     *
     * @return mixed
     */
    public function findByMany(array $data, array $relations = []);

    /**
     * Find multiple records
     *
     * @return object
     */
    public function getWhereIn(array $ids, array $relations = []);

    /**
     * Store a newly created item
     *
     * @return mixed
     */
    public function store(array $data);

    /**
     * Update an existing item
     *
     * @param int|string $id
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Remove an item from storage
     *
     * @param int|string $id
     * @return mixed
     */
    public function destroy($id);

    /**
     * Get count of records
     *
     * @return int
     */
    public function count();
}
