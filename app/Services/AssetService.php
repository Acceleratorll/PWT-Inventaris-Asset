<?php

namespace App\Services;

use App\Repositories\AssetRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AssetService
{
    protected $repository;
    protected $roomService;

    public function __construct(AssetRepository $repository, RoomService $roomService)
    {
        $this->repository = $repository;
        $this->roomService = $roomService;
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function tableAll()
    {
        $datas = $this->repository->all();
        return DataTables::of($datas)
            ->addColumn('id', function ($data) {
                return $data->id;
            })
            ->addColumn('name', function ($data) {
                return $data->name;
            })
            ->addColumn('code', function ($data) {
                return $data->item_code;
            })
            ->addColumn('type', function ($data) {
                return $data->asset_type->name;
            })
            ->addColumn('total', function ($data) {
                return $data->total;
            })
            ->addColumn('note', function ($data) {
                return $data->note;
            })
            ->addColumn('last_move', function ($data) {
                return Carbon::parse($data->last_move_date)->format('D, d-m-y, G:i');
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

    public function showRooms($id)
    {
        $asset = $this->repository->find($id);

        if ($asset) {
            $rooms = $asset->rooms()->withPivot('qty')->get();

            return response()->json($rooms);
        }

        return response()->json(['error' => 'Asset "pc" not found.']);
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
        $asset = $this->repository->create($data);
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
