<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use App\Services\AssetRoomConditionService;
use App\Services\AssetService;
use App\Services\MovementService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    protected $assetRoomConditionService;
    protected $assetService;
    protected $movementService;

    public function __construct(
        MovementService $movementService,
        AssetService $assetService,
        AssetRoomConditionService $assetRoomConditionService,
    ) {
        $this->movementService = $movementService;
        $this->assetService = $assetService;
        $this->assetRoomConditionService = $assetRoomConditionService;
    }

    public function index()
    {
        return view('stock.index');
    }

    public function create()
    {
        return view('stock.create');
    }

    public function getPivotByIdAndRoomIds(Request $request)
    {
        $asset_id = $request->input('asset_id');
        $room_ids = $request->input('room_ids');

        $qty = [];

        foreach ($room_ids as $room_id) {
            $pivotGood = $this->assetRoomConditionService->findByAssetRoomCondition($asset_id, $room_id, 1);
            $pivotBad = $this->assetRoomConditionService->findByAssetRoomCondition($asset_id, $room_id, 2);

            $qty[$room_id] = [
                'qty_good' => $pivotGood->qty ?? 0,
                'qty_bad' => $pivotBad->qty ?? 0,
            ];
        }

        return response()->json($qty);
    }

    public function store(StockRequest $stockRequest)
    {
        $input = $stockRequest->validated();
        if (array_sum($input['qty_good']) + array_sum($input['qty_bad']) > $input['total'] || array_sum($input['qty_good']) + array_sum($input['qty_bad']) < $input['total']) {
            return redirect()->back()->with('error', 'Pastikan memasukkan Total yang benar');
        }
        $this->assetService->addStock($input);
        return redirect()->route('admin.assets.index')->with('success', 'Stock berhasil ditambah');
    }

}
