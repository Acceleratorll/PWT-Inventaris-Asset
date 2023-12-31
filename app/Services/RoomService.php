<?php

namespace App\Services;

use App\Repositories\RoomRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class RoomService
{
    protected $repository;

    public function __construct(RoomRepository $repository)
    {
        $this->repository = $repository;
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
            ->addColumn('location', function ($data) {
                return $data->location;
            })
            ->addColumn('formatted_created_at', function ($data) {
                return Carbon::parse($data->created_at)->format('D, d-m-y, G:i');
            })
            ->addColumn('formatted_updated_at', function ($data) {
                return Carbon::parse($data->updated_at)->format('D, d-m-y, G:i');
            })
            ->addColumn('action', 'partials.button.room')
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

    public function showAssets($id)
    {
        $data = $this->repository->find($id);

        if ($data) {
            $assets = $data->assets()->withPivot('qty')->get();

            return response()->json($assets);
        }

        return response()->json(['error' => 'Room not found.']);
    }

    public function addNewAsset($id, $datas)
    {
        $data = $this->repository->find($id);

        return $data->assets()->updateOrCreate(
            ['asset_id' => $id],
            ['quantity' => $datas->qty]
        );
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }
}
