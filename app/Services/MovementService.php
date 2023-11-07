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
                return $data->qty;
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
        $fromRoom = $this->roomRepository->find($datas->fromRoom);
        $toRoom = $this->roomRepository->find($datas->toRoom);
        $asset = $this->assetRepository->find($datas->assetId);

        if ($fromRoom->id === $toRoom->id) {
            // Same room, no need to move
            return redirect()->back()->with('error', 'Cannot move asset within the same room.');
        }

        $pivotOrigin = $fromRoom->assets()->where('asset_id', $asset->id)->first();
        $pivotDestination = $toRoom->assets()->where('asset_id', $asset->id)->firstOrNew([]);

        if ($pivotOrigin->qty < $datas->qty) {
            return back()->with('error', 'Insufficient assets in the origin room.');
        }

        $pivotOrigin->decrement('qty', $datas->qty);
        $pivotDestination->qty += $datas->qty;
        $pivotDestination->save();

        return $this->movementRepository->create($datas);
    }

    public function assetMovements($assetId)
    {
        return $this->movementRepository->getByAsset($assetId);
    }   

    public function roomAssets($roomId)
    {
        return $this->movementRepository->getByRoom($roomId);
    }

    public function create($data)
    {
        return $this->movementRepository->create($data);
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

        // Calculate the difference in qty between the new qty and the old qty
        $qtyDifference = $data->qty - $movement->qty;

        if ($qtyDifference === 0) {
            return back()->with('warning', 'No changes were made.');
        }

        // Check if there are enough assets in the origin room to accommodate the difference
        $fromRoom = $movement->fromRoom;
        $pivotOrigin = $fromRoom->assets()->where('asset_id', $movement->asset_id)->first();

        if ($pivotOrigin->qty < abs($qtyDifference)) {
            return back()->with('error', 'Insufficient assets in the origin room.');
        }

        // Update the asset qty in the origin room
        $pivotOrigin->qty -= $qtyDifference;
        $pivotOrigin->save();

        // Update the asset qty in the destination room
        $toRoom = $movement->toRoom;
        $pivotDestination = $toRoom->assets()->where('asset_id', $movement->asset_id)->firstOrNew([]);
        $pivotDestination->qty += $qtyDifference;
        $pivotDestination->save();

        // Update the movement record with the new qty
        $movement->qty = $data->qty;
        $movement->save();
    }

    public function delete($id)
    {
        return $this->movementRepository->delete($id);
    }
}
