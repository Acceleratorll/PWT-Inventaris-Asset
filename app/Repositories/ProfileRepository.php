<?php


namespace App\Repositories;

use App\Models\User;

class ProfileRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function search($term)
    {
        return $this->model
            ->where('name', 'LIKE', '%' . $term . '%')
            ->where('email', 'LIKE', '%' . $term . '%')
            ->whereHas('role', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            })
            ->get();
    }

    public function all()
    {
        return $this->model->with('role')->get();
    }

    public function paginate()
    {
        return $this->model->with('role')->paginate(10);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $movement = $this->model->findOrFail($id);
        $movement->update([
            'asset_id' => $data['asset_id'],
            'from_room_id' => $data['from_room_id'],
            'to_room_id' => $data['to_room_id'],
        ]);
        return $movement;
    }

    public function delete($id)
    {
        $movement = $this->model->findOrFail($id);
        $movement->delete();
        return $movement;
    }
}
