<?php

namespace App\Services;

use App\Models\AssetRoom;
use App\Repositories\AssetRepository;
use App\Repositories\MovementRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class MovementService
{
    // protected $assetRoomService;
    protected $assetRepository;
    protected $movementRepository;

    public function __construct(
        // AssetRoomService $assetRoomService,
        AssetRepository $assetRepository,
        MovementRepository $movementRepository,
    ) {
        // $this->assetRoomService = $assetRoomService;
        $this->assetRepository = $assetRepository;
        $this->movementRepository = $movementRepository;
    }

    public function tableAll()
    {
        $datas = $this->movementRepository->getByRoomExcept(2)->paginate(5);
        return DataTables::of($datas)
            ->addColumn('id', function ($data) {
                return $data->id;
            })
            ->addColumn('name', function ($data) {
                return $data->asset->name;
            })
            ->addColumn('fromRoom', function ($data) {
                return $data->fromRoom->name ?? 'Baru';
            })
            ->addColumn('toRoom', function ($data) {
                return $data->toRoom->name;
            })
            ->addColumn('qty', function ($data) {
                return $data->qty;
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
            ->addColumn('action', 'partials.button.movement')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function selectAll($term)
    {
        $datas = $this->movementRepository->search($term);
        $formattedDatas = $datas->map(function ($data) {
            return [
                'id' => $data->id,
                'text' => $data->name
            ];
        });
        return response()->json($formattedDatas);
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

    public function getFromRoomNull()
    {
        return $this->movementRepository->getNullFromRoom();
    }

    public function getByAssetAndFromRoom($asset, $from_room)
    {
        return $this->movementRepository->getByAssetAndFromRoom($asset, $from_room);
    }

    private function getPivot($asset_id, $room_id, $condition_id)
    {
        return AssetRoom::where('asset_id', $asset_id)->where('room_id', $room_id)->where('condition_id', $condition_id)->first();
    }

    public function move($datas)
    {
        $asset = $this->assetRepository->find($datas['asset_id']);

        $pivotFrom = $this->getPivot($datas['asset_id'], $datas['to_room_id'], $datas['condition_id']);
        $pivotTo = $this->getPivot($datas['asset_id'], $datas['to_room_id'], $datas['condition_id']);

        if (!$this->assetIsMoveable($asset)) {
            return redirect()->back()->withErrors(['error' => 'Asset cannot be moved. Please edit Asset Type']);
        }
        if (!$pivotFrom) {
            return redirect()->back()->withErrors('error', 'This room doesnt have asset ' . $asset->name);
        }
        if ($datas['from_room_id'] === $datas['to_room_id'] && $pivotFrom->condition_id == $datas['condition_id']) {
            return redirect()->back()->withErrors('error', 'Cannot move asset within the same room and same condition.');
        }
        if ($pivotFrom->qty < $datas['qty']) {
            return redirect()->back()->withErrors('error', 'Insufficient assets in the origin room.');
        }

        $pivotFrom->update([
            'qty' => $pivotFrom->qty - $datas['qty'],
        ]);

        if ($pivotTo) {
            return $pivotTo->update(['qty' => $pivotTo->qty + $datas['qty']]);
        } else {
            return AssetRoom::create(['asset_id' => $datas['asset_id'], 'room_id' => $datas['to_room_id'], 'qty' => $datas['qty'], 'condition_id' => $datas['condition_id']]);
        }
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
        return $this->movementRepository->create($data);
    }

    public function insert($data)
    {
        return $this->movementRepository->insert($data);
    }

    public function search($term)
    {
        return $this->movementRepository->search($term);
    }

    public function find($id)
    {
        return $this->movementRepository->find($id);
    }

    // public function update($id, $data)
    // {
    //     $movement = $this->movementRepository->find($id);

    //     if ($this->noChanges($movement, $data)) {
    //         return back()->with('warning', 'No changes were made.');
    //     }

    //     $asset = $movement->asset;
    //     $newAsset = $this->assetRepository->find($data['asset_id']);
    //     $pivotFrom = $this->findByAssetRoomCondition($movement->from_room_id, $asset, $movement->condition_id);
    //     $pivotTo = $this->findByAssetRoomCondition($movement->to_room_id, $asset, $movement->condition_id);
    //     $newPivotFrom = $this->findByAssetRoomCondition($data['from_room_id'], $newAsset, $data['condition_id']);
    //     $newPivotTo = $this->findByAssetRoomCondition($data['to_room_id'], $newAsset, $data['condition_id']);

    //     if ($newAsset->asset_type->isMoveable === false) {
    //         return redirect()->back()->with('error', 'Asset cannot be moved. Please edit Asset Type');
    //     }
    //     if (!$newPivotFrom) {
    //         return redirect()->back()->with('error', 'This room doesnt have asset ' . $asset->name);
    //     }

    //     $qtyDifference = $data['qty'] - $movement->qty;

    //     if ($this->insufficientAssets($newPivotFrom, $qtyDifference)) {
    //         return back()->with('error', 'Insufficient assets in the origin room.');
    //     }

    //     $asset->rooms()->updateExistingPivot($pivotFrom, ['qty' => $pivotTo->qty + $data['qty']]);
    //     $newAsset->rooms()->updateExistingPivot($newPivotFrom, ['qty' => $newpivotTo->qty - $data['qty']]);

    //     if ($newPivotTo) {
    //         $newAsset->rooms()->updateExistingPivot($newPivotTo, ['qty' => $newpivotTo->qty + $data['qty']]);
    //     } else {
    //         $newAsset->rooms()->attach($data['to_room_id'], ['qty' => $data['qty'], 'condition_id' => $data['condition_id']]);
    //     }

    //     $movement->update($data);
    //     return true;
    // }

    public function delete($id)
    {
        return $this->movementRepository->delete($id);
    }

    private function noChanges($movement, $data)
    {
        return collect(['qty', 'condition_id', 'to_room_id', 'from_room_id'])
            ->every(function ($field) use ($movement, $data) {
                return $movement->$field == $data[$field];
            });
    }

    private function assetIsMoveable($asset)
    {
        return $asset->asset_type->isMoveable;
    }
}
