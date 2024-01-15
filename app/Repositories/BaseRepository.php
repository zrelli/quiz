<?php
namespace App\Repositories;
use Exception;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use function Laravel\Prompts\search;
use function PHPUnit\Framework\isEmpty;
abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;
    /**
     * @var Application
     */
    protected $app;
    protected $paginationFilter = [];
    protected $relationQuery = [];
    /**
     * 
     * @throws \Exception
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }
    /**
     * Get searchable fields array
     *
     * @return array
     */
    abstract public function getFieldsSearchable();
    /**
     * Configure the Model
     *
     * @return string
     */
    abstract public function model();
    /**
     * Make Model instance
     *
     * @return Model
     *
     * @throws \Exception
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        return $this->model = $model;
    }
    public function setPaginationFilter($filter)
    {
        $this->paginationFilter = $filter;
    }
    public function setRelationQuery($value)
    {
        $this->relationQuery = $value;
    }
    /**
     * Paginate records for scaffold.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage, $columns = ['*'])
    {
        $search = $this->paginationFilter;
        $query = $this->allQuery($search);
        $this->paginationFilter = [];
        // return $query->paginate($perPage, $columns);
        return $query->simplePaginate($perPage, $columns);
    }
    /**
     * Build a query for retrieving all records.
     *
     * @param  array  $search
     * @param  int|null  $skip
     * @param  int|null  $limit
     * @return Builder
     */
    public function allQuery($search = [], $skip = null, $limit = null)
    {
        $query = $this->model->newQuery();
        if (count($search)) {
            foreach ($search as $key => $value) {
                if (in_array($key, $this->getFieldsSearchable())) {
                    $query->where($key, $value);
                }
            }
        }
        if (!is_null($skip)) {
            $query->skip($skip);
        }
        if (!is_null($limit)) {
            $query->limit($limit);
        }
        return $query;
    }
    /**
     * Retrieve all records with given filter criteria
     *
     * @param  array  $search
     * @param  int|null  $skip
     * @param  int|null  $limit
     * @param  array  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Builder[]|Collection
     */
    public function all($search = [], $skip = null, $limit = null, $columns = ['*'])
    {
        $query = $this->allQuery($search, $skip, $limit);
        return $query->get($columns);
    }
    /**
     * Create model record
     *
     * @param  array  $input
     * @return Model
     */
    public function create($input)
    {
        if(!isEmpty($this->relationQuery)){
            $input = [...$this->relationQuery,...$input];
        }
        $model = $this->model->newInstance($input);
        $model->save();
        $this->relationQuery = [];
        return $model;
    }
    /**
     * Find model record for given id
     *
     * @param  int  $id
     * @param  array  $columns
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function find($id, $columns = ['*'])
    {
        $query = $this->model->newQuery();
        return $query->find($id, $columns);
    }
    /**
     * Update model record for given id
     *
     * @param  array  $input
     * @param  int  $id
     * @return Builder|Builder[]|Collection|Model
     */
    public function update($input, $id)
    {
        $query = $this->model->newQuery();
        $model = $query->findOrFail($id);
        $model->fill($input);
        $model->save();
        return $model;
    }
    /**
     * @param  int  $id
     * @return bool|mixed|null
     *
     * @throws Exception
     */
    public function delete($model)
    {
        return $model->delete();
    }
    /**
     * @param  int  $id
     * @param  array  $columns
     * @return mixed
     */
    public function findWithoutFail($id, $columns = ['*'])
    {
        try {
            return $this->find($id, $columns);
        } catch (Exception $e) {
            return;
        }
    }
}
