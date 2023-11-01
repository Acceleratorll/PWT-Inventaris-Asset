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
        return $this->model->find($id);
    }

    public function search($term)
    {
        return $this->model
            ->where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('item_code', 'LIKE', '%' . $term . '%')
            ->orWhere('note', 'LIKE', '%' . $term . '%')
            ->orWhereHas('assetType', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            })
            ->orWhereHas('room', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            })
            ->get();
    }

    public function all()
    {
        return $this->model->with('asset_type', 'room')->get();
    }

    public function paginate(int $no)
    {
        return $this->model->with('asset_type', 'room')->paginate($no);
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
            'room_id' => $data['room_id'],
            'item_code' => $data['item_code'],
            'name' => $data['name'],
            'acquition' => $data['acquition'],
            'total' => $data['total'],
            'last_move_date' => $data['last_move_date'],
            'condition' => $data['condition'],
            'note' => $data['note'],
        ]);
        return $asset;
    }

    public function delete($id)
    {
        $asset = $this->model->find($id);
        return $asset->delete();
    }
}
