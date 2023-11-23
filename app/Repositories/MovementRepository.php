<?php


namespace App\Repositories;

use App\Models\Movement;
use Illuminate\Database\Eloquent\Collection;

class MovementRepository
{
    protected $model;

    public function __construct(Movement $model)
    {
        $this->model = $model;
    }

    public function find($id): Movement
    {
        return $this->model->find($id);
    }

    public function search($term): Collection
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
            ->orWhereHas('condition', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            })
            ->orWhereHas('qty', function ($query) use ($term) {
                $query->where('qty', 'LIKE', '%' . $term . '%');
            })
            ->get();
    }

    public function getByAsset($id): Collection
    {
        return $this->model->where('asset_id', $id)
            ->with(['fromRoom', 'toRoom', 'asset'])
            ->get();
    }

    public function getByRoom($id): Collection
    {
        return $this->model->where('to_room_id', $id)
            ->with(['fromRoom', 'toRoom', 'asset'])
            ->get();
    }

    public function getByRoomExcept($id = 2): Collection
    {
        return $this->model->where('to_room_id', '!=', $id)
            ->with(['fromRoom', 'toRoom', 'asset']);
    }

    public function getNullFromRoom(): Collection
    {
        return $this->model->where('from_room_id', null)
            ->with(['fromRoom', 'toRoom', 'asset'])
            ->get();
    }

    public function getByAssetAndFromRoom($asset, $from_room): Collection
    {
        return $this->model->where('from_room_id', $from_room)
            ->where('asset_id', $asset)
            ->with(['fromRoom', 'toRoom', 'asset'])
            ->get();
    }

    public function all(): Collection
    {
        return $this->model->with('asset', 'fromRoom', 'toRoom')->get();
    }

    public function paginate($no)
    {
        return $this->model->with('asset', 'fromRoom', 'toRoom')->paginate($no);
    }

    public function create($data): Movement
    {
        return $this->model->create($data);
    }

    public function insert($data): Movement
    {
        return $this->model->insert($data);
    }

    public function update($id, $data): Movement
    {
        $movement = $this->model->find($id);
        $movement->update([
            'asset_id' => $data['asset_id'],
            'from_room_id' => $data['from_room_id'],
            'to_room_id' => $data['to_room_id'],
            'qty' => $data['qty'],
            'condition' => $data['condition'],
        ]);
        return $movement;
    }

    public function delete($id): Movement
    {
        $movement = $this->model->find($id);
        return $movement->delete();
    }
}
