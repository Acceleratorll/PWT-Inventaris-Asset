<?php

namespace App\Services;

use App\Repositories\AssetRepository;
use App\Repositories\MovementRepository;
use App\Repositories\RoomRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class MovementService
{
    protected $roomRepository;
    protected $assetRepository;
    protected $movementRepository;

    public function __construct(
        RoomRepository $roomRepository,
        AssetRepository $assetRepository,
        MovementRepository $movementRepository,
    ) {
        $this->roomRepository = $roomRepository;
        $this->assetRepository = $assetRepository;
        $this->movementRepository = $movementRepository;
    }

    public function tableAll()
    {
        $all = $this->movementRepository->all();
        return $this->table($all);
    }

    public function table($query)
    {
        return DataTables::of($query)
            ->addColumn('id', function ($data) {
                return $data->id;
            })
            ->addColumn('name', function ($data) {
                return $data->asset->name;
            })
            ->addColumn('fromRoom', function ($data) {
                return $data->fromRoom->name;
            })
            ->addColumn('toRoom', function ($data) {
                return $data->toRoom->name;
            })
            ->addColumn('qty', function ($data) {
                return $data['qty'];
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

    public function move($datas)
    {
        $fromRoom = $this->roomRepository->find($datas['from_room_id']);
        $toRoom = $this->roomRepository->find($datas['to_room_id']);
        $asset = $this->assetRepository->find($datas['asset_id']);

        if ($fromRoom->id === $toRoom->id) {
            return redirect()->back()->with('error', 'Cannot move asset within the same room.');
        } else if ($asset->asset_type->isMoveable === false) {
            return redirect()->back()->with('error', 'Asset cannot be moved. Please edit Asset Type');
        }

        $pivotOrigin = $fromRoom->assets()->where('asset_id', $asset->id)->first();
        $pivotDestination = $toRoom->assets()->where('asset_id', $asset->id)->first();

        if (!$pivotOrigin || $pivotOrigin->pivot->qty < $datas['qty']) {
            return redirect()->back()->with('error', 'Insufficient assets in the origin room.');
        }

        if ($pivotDestination) {
            $toRoom->assets()->updateExistingPivot($asset, ['qty' => $pivotDestination->pivot->qty + $datas['qty']]);
        } else {
            $toRoom->assets()->attach($asset, ['qty' => $datas['qty']]);
        }

        $fromRoom->assets()->updateExistingPivot($asset, ['qty' => $pivotOrigin->pivot->qty - $datas['qty']]);

        return $this->movementRepository->create($datas);
    }


    public function assetRooms($assetId)
    {
        return $this->movementRepository->getByAsset($assetId);
    }

    public function roomAssets($roomId)
    {
        return $this->movementRepository->getByRoom($roomId);
    }

    public function create($data)
    {
        return $this->move($data);
    }

    public function search($term)
    {
        return $this->movementRepository->search($term);
    }

    public function find($id)
    {
        return $this->movementRepository->find($id);
    }

    public function update($id, $data)
    {
        $movement = $this->movementRepository->find($id);
        $qtyDifference = $data['qty'] - $movement->qty;

        if ($qtyDifference === 0) {
            return back()->with('warning', 'No changes were made.');
        }

        $fromRoom = $movement->fromRoom;
        $toRoom = $movement->toRoom;
        $pivotOrigin = $fromRoom->assets()->where('asset_id', $movement->asset_id)->first();
        $pivotDestination = $toRoom->assets()->where('asset_id', $movement->asset_id)->first();

        if ($pivotOrigin->pivot->qty < abs($qtyDifference)) {
            return back()->with('error', 'Insufficient assets in the origin room.');
        }

        // Update the asset qty in the origin room
        $fromRoom->assets()->updateExistingPivot($pivotOrigin, ['qty' => $pivotOrigin->pivot->qty - $qtyDifference]);
        $toRoom->assets()->updateExistingPivot($pivotDestination, ['qty' => $pivotDestination->pivot->qty + $qtyDifference]);

        $movement->qty = $data['qty'];
        $movement->save();
    }

    public function delete($id)
    {
        return $this->movementRepository->delete($id);
    }
}
