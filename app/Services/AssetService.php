<?php

namespace App\Services;

use App\Repositories\AssetRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AssetService
{
    protected $repository;

    public function __construct(AssetRepository $repository)
    {
        $this->repository = $repository;
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
            ->addColumn('room', function ($data) {
                return $data->room->name;
            })
            ->addColumn('total', function ($data) {
                return $data->total;
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
