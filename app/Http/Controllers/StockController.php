<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use App\Services\AssetService;
use App\Services\MovementService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    protected $assetService;
    protected $movementService;

    public function __construct(
        MovementService $movementService,
        AssetService $assetService,
    ) {
        $this->movementService = $movementService;
        $this->assetService = $assetService;
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
            $pivotGood = $this->assetService->getPivotByCondition($asset_id, $room_id, 'good');
            $pivotBad = $this->assetService->getPivotByCondition($asset_id, $room_id, 'bad');

            $qty[$room_id] = [
                'qty_good' => $pivotGood->pivot->qty ?? 0,
                'qty_bad' => $pivotBad->pivot->qty ?? 0,
            ];
        }

        return response()->json($qty);
    }

    public function store(StockRequest $stockRequest)
    {
        $input = $stockRequest->validated();
        $checkTotal = $this->assetService->checkQty($input['qty_good'], $input['qty_bad'], $input['total']);
        if ($checkTotal === false) {
            return redirect()->back()->with('error', 'Pastikan memasukkan Total yang benar');
        }
        $this->assetService->addStock($input);
        return redirect()->route('admin.assets.index')->with('success', 'Stock berhasil ditambah');
    }
}
