<?php


namespace App\Repositories;

use App\Models\AssetType;

class AssetTypeRepository
{
    protected $model;

    public function __construct(AssetType $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function search($term)
    {
        return $this->model
            ->where('name', 'LIKE', '%' . $term . '%')
            ->get();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $type = $this->model->find($id);
        $type->update([
            'name' => $data['name'],
            'isMoveable' => $data['isMoveable'],
        ]);
        return $type;
    }

    public function delete($id)
    {
        $type = $this->model->find($id);
        return $type->delete();
    }
}
