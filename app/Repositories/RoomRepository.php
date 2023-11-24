<?php


namespace App\Repositories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;

class RoomRepository
{
    protected $model;

    public function __construct(Room $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->with('assetRoomConditions.asset', 'assetRoomConditions.condition')->find($id);
    }

    public function search($term): Collection
    {
        return $this->model
            ->with('assetRoomConditions.asset', 'assetRoomConditions.condition')
            ->where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('location', 'LIKE', '%' . $term . '%')
            ->get();
    }

    public function all(): Collection
    {
        return $this->model->with('assetRoomConditions.asset', 'assetRoomConditions.condition')->get();
    }

    public function paginate()
    {
        return $this->model->with('assetRoomConditions.asset', 'assetRoomConditions.condition')->paginate(10);
    }

    public function create($data): Room
    {
        return $this->model->create($data);
    }

    public function update($id, $data): Room
    {
        $room = $this->find($id);
        $room->update([
            'name' => $data['name'],
            'location' => $data['location'],
        ]);
        return $room;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }
}
