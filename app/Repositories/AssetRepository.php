<?php


namespace App\Repositories;

use App\Models\Asset;

class AssetRepository
{
    protected $model;

    public function __construct(Asset $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->with('asset_type', 'rooms', 'movements')->findOrFail($id);
    }

    public function findByCode($code)
    {
        return $this->model->with('asset_type', 'rooms', 'movements')->where('item_code', $code)->first();
    }

    public function getPivotByIdAndRoomId($asset_id, $room_id)
    {
        return $this->find($asset_id)->rooms()->where('room_id', $room_id);
    }

    public function search($term)
    {
        return $this->model->with('asset_type', 'rooms', 'movements')
            ->where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('item_code', 'LIKE', '%' . $term . '%')
            ->orWhere('acquition', 'LIKE', '%' . $term . '%')
            ->orWhere('total', 'LIKE', '%' . $term . '%')
            ->orWhere('last_move_date', 'LIKE', '%' . $term . '%')
            ->orWhere('note', 'LIKE', '%' . $term . '%')
            ->orWhereHas('asset_type', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            })
            ->get();
    }

    public function all()
    {
        return $this->model->with('asset_type', 'rooms')->get();
    }

    public function paginate(int $no)
    {
        return $this->model->with('asset_type', 'rooms')->paginate($no);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $asset = $this->model->find($id);
        $asset->update([
            'asset_type_id' => $data['asset_type_id'],
            'item_code' => $data['item_code'],
            'name' => $data['name'],
            'acquition' => $data['acquition'],
            'total' => $data['total'],
            'last_move_date' => $data['last_move_date'],
            'note' => $data['note'],
        ]);
        return $asset;
    }

    public function delete($id)
    {
        $asset = $this->model->findOrFail($id);
        return $asset->delete();
    }
}
