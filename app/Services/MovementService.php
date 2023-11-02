<?php

namespace App\Services;

use App\Models\Movement;
use App\Repositories\MovementRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class MovementService
{
    protected $repository;

    public function __construct(MovementRepository $repository)
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
            ->addColumn('asset_id', function ($data) {
                return $data->asset_id;
            })
            ->addColumn('from', function ($data) {
                return $data->from;
            })
            ->addColumn('to', function ($data) {
                return $data->to_room_id;
            })
            ->addColumn('formatted_created_at', function ($data) {
                return Carbon::parse($data->created_at)->format('D, d-m-y, G:i');
            })
            ->addColumn('formatted_updated_at', function ($data) {
                return Carbon::parse($data->updated_at)->format('D, d-m-y, G:i');
            })
            ->addColumn('action', 'partials.button.movement')
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
}
