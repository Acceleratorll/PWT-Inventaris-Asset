<?php

namespace App\Services;

use App\Models\AssetRoom;
use App\Repositories\AssetRepository;
use App\Repositories\MovementRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class MovementService
{
    protected $movementRepository;

    public function __construct(
        MovementRepository $movementRepository,
    ) {
        $this->movementRepository = $movementRepository;
    }

    public function tableAll()
    {
        $datas = $this->movementRepository->all();
        return $this->table($datas);
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
            ->addColumn('condition', function ($data) {
                return $data->condition->name;
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

    public function validation($asset, $pivotFrom, $datas)
    {
        if (!$this->assetIsMoveable($asset)) {
            return redirect()->back()->withErrors(['error' => 'Asset cannot be moved. Please edit Asset Type']);
        } else if (!$pivotFrom) {
            return redirect()->back()->withErrors('error', 'This room doesnt have asset ' . $asset->name);
        } else if ($datas['from_room_id'] === $datas['to_room_id']) {
            return redirect()->back()->withErrors('error', 'Cannot move asset within the same room and same condition.');
        } else if ($pivotFrom->qty < $datas['qty']) {
            return redirect()->back()->withErrors('error', 'Insufficient assets in the origin room.');
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

    public function update($id, $data)
    {
        $movement = $this->find($id);

        $movement->update($data);
        return $movement;
    }

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
