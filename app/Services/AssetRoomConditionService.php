<?php

namespace App\Services;

use App\Repositories\AssetRoomConditionRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AssetRoomConditionService
{
    protected $repository;
    protected $assetService;
    protected $roomService;

    public function __construct(
        AssetRoomConditionRepository $repository,
    ) {
        $this->repository = $repository;
    }

    public function tableAll()
    {
        $datas = $this->repository->all();
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
        $datas = $this->repository->search($term);
        $formattedDatas = $datas->map(function ($data) {
            return [
                'id' => $data->id,
                'text' => $data->name
            ];
        });
        return response()->json($formattedDatas);
    }

    public function findByAsset($asset_id)
    {
        return $this->repository->findByAsset($asset_id)->first();
    }

    public function findByAssetRoom($asset_id, $room_id)
    {
        return $this->repository->findByAssetRoom($asset_id, $room_id)->get();
    }

    public function findByAssetRoomCondition($asset_id, $room_id, $condition_id)
    {
        return $this->repository->findByAssetRoomCondition($asset_id, $room_id, $condition_id)->first();
    }

    public function search($term)
    {
        return $this->repository->search($term);
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function create($data)
    {
        return $this->repository->create($data);
    }

    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
