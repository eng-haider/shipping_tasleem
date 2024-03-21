<?php
namespace App\Http\Repositories;

use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

Abstract class BaseRepository implements BaseInterfaceRepository{
    protected $model;
    protected $modelName;
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->modelName = class_basename($model);
    }
    public function getList($take = 10): LengthAwarePaginator
    {
        $result = QueryBuilder::for($this->model)
                                ->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns);
        return $result->paginate($take);
    } 
    public function show($id)
    {
        return QueryBuilder::for($this->model)
                            ->allowedIncludes($this->model->relations)
                            ->findOrFail($id);
    }
    public function showByUuid($id)
    {
        return QueryBuilder::for($this->model)
                            ->allowedIncludes($this->model->relations)
                            ->where('uuid', $id)
                            ->firstOrFail();
    }
    public function check($id)
    {
        return $this->model->where('id', $id)->exists();
    }
    public function create($data)
    {
        return $this->model->create($data);
    }
    public function insert($data)
    {
        return $this->model->insert($data);
    }
    public function update($id, $values)
    {
        $item = $this->model->findOrFail($id);
        $item->update($values);
        return $item;
    }
    public function delete($model)
    {
        $model->delete();
        return $model;
    }
    //force delete
    public function forceDelete($id)
    {
        $item = $this->model->findOrFail($id);
        $item->forceDelete();
        return $item;
    }
    //restore
    public function restore($id)
    {
        $item = $this->model->withTrashed()->findOrFail($id);
        $item->restore();
        return $item;
    }
    public function getDestroyedList($take = 10): LengthAwarePaginator
    {
        $result = QueryBuilder::for($this->model)
                                ->onlyTrashed()
                                ->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns);
        return $result->paginate($take);
    }
}