<?php


namespace App\Repositories;

use App\Models\Movement;

class MovementRepository
{
    protected $model;

    public function __construct(Movement $model)
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
            ->orWhereHas('asset', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            })
            ->orWhereHas('fromRoom', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            })
            ->orWhereHas('toRoom', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            })
            ->get();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginate()
    {
        return $this->model->with('asset', 'fromRoom', 'toRoom')->paginate(10);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $movement = $this->model->find($id);
        $movement->update([
            'asset_id' => $data['asset_id'],
            'from_room_id' => $data['from_room_id'],
            'to_room_id' => $data['to_room_id'],
        ]);
        return $movement;
    }

    public function delete($id)
    {
        $movement = $this->model->find($id);
        return $movement->delete();
    }
}
