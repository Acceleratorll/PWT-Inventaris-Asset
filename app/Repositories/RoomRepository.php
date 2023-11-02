<?php


namespace App\Repositories;

use App\Models\Room;

class RoomRepository
{
    protected $model;

    public function __construct(Room $model)
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
            ->orWhere('location', 'LIKE', '%' . $term . '%')
            ->get();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginate()
    {
        return $this->model->with('assets')->paginate(10);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $room = $this->model->find($id);
        $room->update([
            'name' => $data['name'],
            'location' => $data['location'],
        ]);
        return $room;
    }

    public function delete($id)
    {
        $room = $this->model->find($id);
        return $room->delete();
    }
}
