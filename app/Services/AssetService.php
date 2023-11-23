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
    protected $assetRoomConditionService;

    public function __construct(
        AssetRepository $repository,
        // AssetRoomConditionService $assetRoomConditionService,
        // RoomService $roomService,
        // MovementService $movementService
    ) {
        $this->repository = $repository;
        // $this->roomService = $roomService;
        // $this->assetRoomConditionService = $assetRoomConditionService;
        // $this->movementService = $movementService;
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
        return $this->repository->create($data);
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
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    private function attach($asset, $data, $roomId)
    {
        $room = $this->roomService->find($roomId);

        $asset->assetRoomConditions()->create([
            'room_id' => $room->id,
            'qty' => $data['qty_good'][$roomId],
            'condition_id' => 1,
        ]);

        $asset->assetRoomConditions()->create([
            'room_id' => $room->id,
            'qty' => $data['qty_bad'][$roomId],
            'condition_id' => 2,
        ]);
    }

    private function MoveData($asset, $data, $roomId)
    {
        $moveDataBad = [
            [
                'asset_id' => $asset->id,
                'from_room_id' => 1,
                'to_room_id' => $roomId,
                'qty' => $data['qty_good'][$roomId],
                'condition' => 'good',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'asset_id' => $asset->id,
                'from_room_id' => 1,
                'to_room_id' => $roomId,
                'qty' => $data['qty_bad'][$roomId],
                'condition' => 'bad',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $this->movementService->insert($moveDataBad);
    }

    public function checkQty($totalGood, $totalBad, $totalAll)
    {
        if (array_sum($totalGood) + array_sum($totalBad) !== $totalAll) {
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
        return  $this->assetRoomConditionService->findByAssetRoomCondition($asset_id, $room_id, $condition);
    }
}
