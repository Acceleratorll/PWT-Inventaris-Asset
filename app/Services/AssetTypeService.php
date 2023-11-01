<?php

namespace App\Services;

use App\Repositories\AssetTypeRepository;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\DataTables\Facades\DataTables;

class AssetTypeService
{
    protected $repository;

    public function __construct(AssetTypeRepository $repository)
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
            ->addColumn('code', function ($data) {
                return $data->item_code;
            })
            ->addColumn('type', function ($data) {
                return $data->asset_type->name;
            })
            ->addColumn('room', function ($data) {
                return $data->room->name;
            })
            ->addColumn('total', function ($data) {
                return $data->total;
            })
            ->addColumn('acquition', function ($data) {
                return $data->acquition;
            })
            ->addColumn('condition', function ($data) {
                return $data->condition;
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
}
