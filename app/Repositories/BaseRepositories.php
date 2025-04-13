<?php

namespace App\Repositories;

use Illuminate\{
    Database\Eloquent\Model,
    Database\Eloquent\Collection
};

abstract class BaseRepositories{

    public function __construct(protected Model $model){}

    public function findAll(): Collection{
        return $this->model->all();
    }

    public function findFirst(): ?Model{
        return $this->model->first();
    }
    
    public function findBy(string $field, string|int $value): ?Model
    {
        return $this->model->where($field, $value)->first();
    }
    public function find(int $id): ?Model{
        return $this->model->find($id);
    }

    public function create(?array $data): Model{
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool{
        $record = $this->model->find($id);
        if ($record) {
            return $record->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $record = $this->model->find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}