<?php


namespace App\Repositories;

use App\Models\AssetRoomCondition;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Yajra\DataTables\Facades\DataTables;

class AssetRoomConditionRepository
{
    protected $model;

    public function __construct(AssetRoomCondition $model)
    {
        $this->model = $model;
    }

    public function tableAll($datas)
    {
        return DataTables::of($datas)
            ->addColumn('id', function ($data) {
                return $data->id;
            })
            ->addColumn('room', function ($data) {
                return $data->room->name;
            })
            ->addColumn('asset', function ($data) {
                return $data->asset->name;
            })
            ->addColumn('condition', function ($data) {
                return $data->condition->name;
            })
            ->addColumn('formatted_created_at', function ($data) {
                return Carbon::parse($data->created_at)->format('D, d-m-y, G:i');
            })
            ->addColumn('formatted_updated_at', function ($data) {
                return Carbon::parse($data->updated_at)->format('D, d-m-y, G:i');
            })
            ->addColumn('action', 'partials.button.asset')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function selectAll($term)
    {
        $datas = $this->search($term);
        $formattedDatas = $datas->map(function ($data) {
            return [
                'id' => $data->id,
                'text' => $data->name
            ];
        });
        return response()->json($formattedDatas);
    }

    public function find($id): AssetRoomCondition
    {
        return $this->model->with('asset', 'room', 'condition')->findOrFail($id);
    }

    public function findByAsset($id)
    {
        return $this->model->with('asset', 'room', 'condition')->where('asset_id', $id);
    }

    public function findByRoom($room_id)
    {
        return $this->model->with('asset', 'room', 'condition')->where('room_id', $room_id);
    }

    public function findByCondition($id)
    {
        return $this->model->with('asset', 'room', 'condition')->where('condition_id', $id);
    }

    public function findByAssetRoom($asset_id, $room_id)
    {
        return $this->model->with('asset', 'room', 'condition')->where('asset_id', $asset_id)->where('room_id', $room_id);
    }

    public function findByAssetRoomCondition($asset_id, $room_id, $condition_id)
    {
        return $this->model->where('room_id', $room_id)->where('asset_id', $asset_id)->where('condition_id', $condition_id);
    }

    public function search($term): Collection
    {
        return $this->model->with('asset', 'room', 'condition')
            ->orWhereHas('asset', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            })
            ->orWhereHas('room', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            })
            ->orWhereHas('condition', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%');
            })
            ->orWhere('qty', 'LIKE', '%' . $term . '%')
            ->get();
    }

    public function all(): Collection
    {
        return $this->model->with('asset', 'room', 'condition')->get();
    }

    public function paginate(int $no)
    {
        return $this->model->with('asset', 'room', 'condition')->paginate($no);
    }

    public function create($data): AssetRoomCondition
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $asset = $this->model->find($id);
        return $asset->update($data);
    }

    public function delete($id)
    {
        $asset = $this->model->findOrFail($id);
        return $asset->delete();
    }
}
