<?php

namespace App\Services;

use App\Repositories\AssetRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AssetService
{
    protected $repository;
    protected $roomService;
    protected $movementService;

    public function __construct(
        AssetRepository $repository,
        RoomService $roomService,
        MovementService $movementService
    ) {
        $this->repository = $repository;
        $this->roomService = $roomService;
        $this->movementService = $movementService;
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

        foreach ($data['room_id'] as $roomId) {
            $this->attach($asset, $data, $roomId);

            $this->MoveData($asset, $data, $roomId);
        }
    }

    public function addStock($data)
    {
        $asset = $this->repository->find($data['asset_id']);

        if (!$asset) {
            return redirect()->back()->with('error', 'Asset tidak ditemukan, Mungkin terhapus atau terjadi perubahan');
        }

        $this->populateData($asset, $data);
        $asset = $asset->update(['total' => $asset->total + $data['total']]);
        if ($asset) {
            return redirect()->back()->with('error', 'Something went wrong ! Tidak bisa update total');
        }
        return true;
    }

    public function update($id, $data)
    {
        $asset = $this->repository->update($id, $data);

        $asset->rooms()->detach();

        foreach ($data['room_id'] as $roomId) {
            $this->attach($asset, $data, $roomId);

            $movements = $this->movementService->getByAssetAndFromRoom($asset->id, null);
            foreach ($movements as $movement) {
                $movement->delete();
            }

            $this->MoveData($asset, $data, $roomId);
        }
    }

    public function delete($id)
    {
        $movements = $this->movementService->getByAssetAndFromRoom($id, null);
        foreach ($movements as $movement) {
            $movement->delete();
        }
        return $this->repository->delete($id);
    }

    private function attach($asset, $data, $roomId)
    {
        $room = $this->roomService->find($roomId);

        $asset->rooms()->attach($room, [
            'qty' => $data['qty_good'][$roomId],
            'condition' => 'good',
        ]);

        $asset->rooms()->attach($room, [
            'qty' => $data['qty_bad'][$roomId],
            'condition' => 'bad',
        ]);
    }

    private function MoveData($asset, $data, $roomId)
    {
        $moveDataGood = [
            'asset_id' => $asset->id,
            'from_room_id' => null,
            'to_room_id' => $roomId,
            'qty' => $data['qty_good'][$roomId],
            'condition' => 'good',
        ];

        $moveDataBad = [
            'asset_id' => $asset->id,
            'from_room_id' => null,
            'to_room_id' => $roomId,
            'qty' => $data['qty_bad'][$roomId],
            'condition' => 'bad',
        ];

        $this->movementService->create($moveDataGood);
        $this->movementService->create($moveDataBad);
    }

    public function checkQty($totalGood, $totalBad, $totalAll)
    {
        if (array_sum($totalGood) + array_sum($totalBad) > $totalAll || array_sum($totalGood) + array_sum($totalBad) < $totalAll) {
            return false;
        }
        return true;
    }

    private function populateData($asset, $data)
    {
        foreach ($data['room_id'] as $roomId) {
            $room = $this->roomService->find($roomId);
            $existingRoom = $asset->rooms()->where('room_id', $roomId)->exists();

            if ($existingRoom === false) {
                $asset->rooms()->attach($room, [
                    'qty' => $data['qty_good'][$roomId],
                    'condition' => 'good',
                ]);

                $asset->rooms()->attach($room, [
                    'qty' => $data['qty_bad'][$roomId],
                    'condition' => 'bad',
                ]);
            } else {
                $pivotGood = $asset->rooms()->where('condition', 'good')->where('asset_id', $asset->id)->first();
                $asset->rooms()->updateExistingPivot($pivotGood, ['qty' => $pivotGood->pivot->qty + $data['qty_good'][$roomId]]);

                $pivotBad = $asset->rooms()->where('condition', 'bad')->where('asset_id', $asset->id)->first();
                $asset->rooms()->updateExistingPivot($pivotBad, ['qty' => $pivotBad->pivot->qty + $data['qty_bad'][$roomId]]);
            }

            $this->MoveData($asset, $data, $roomId);
        }
    }

    public function getPivotByCondition($asset_id, $room_id, $condition)
    {
        $pivot = $this->repository->getPivotByIdAndRoomId($asset_id, $room_id);
        return $pivot->where('condition', $condition)->first();
    }
}
