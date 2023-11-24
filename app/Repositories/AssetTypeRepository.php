<?php


namespace App\Repositories;

use App\Models\AssetType;
use Illuminate\Database\Eloquent\Collection;

class AssetTypeRepository
{
    protected $model;

    public function __construct(AssetType $model)
    {
        $this->model = $model;
    }

    public function find($id): AssetType
    {
        return $this->model->with('assets')->find($id);
    }

    public function search($term): Collection
    {
        return $this->model
            ->with('assets')
            ->where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('isMoveable', 'LIKE', '%' . $term . '%')
            ->get();
    }

    public function all(): Collection
    {
        return $this->model->with('assets')->get();
    }

    public function paginate($no)
    {
        return $this->model->with('assets')->paginate($no);
    }

    public function create($data): AssetType
    {
        return $this->model->create($data);
    }

    public function update($id, $data): AssetType
    {
        $type = $this->find($id);
        $type->update([
            'name' => $data['name'],
            'isMoveable' => $data['isMoveable'],
        ]);
        return $type;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }
}
