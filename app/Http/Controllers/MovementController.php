<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementRequest;
use App\Repositories\MovementRepository;
use App\Services\AssetRoomConditionService;
use App\Services\AssetService;
use App\Services\MovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovementController extends Controller
{
    protected $movementService;
    protected $movementRepository;
    protected $assetService;
    protected $assetRoomConditionService;

    public function __construct(
        MovementService $movementService,
        MovementRepository $movementRepository,
        AssetRoomConditionService $assetRoomConditionService,
        AssetService $assetService,
    ) {
        $this->movementService = $movementService;
        $this->movementRepository = $movementRepository;
        $this->assetRoomConditionService = $assetRoomConditionService;
        $this->assetService = $assetService;
    }

    public function table()
    {
        $query = $this->movementRepository->all();
        return $this->movementService->table($query);
    }

    public function index()
    {
        return view('movement.index');
    }

    public function create()
    {
        return view('movement.create');
    }

    public function store(MovementRequest $movementRequest)
    {
        $input = $movementRequest->validated();
        $asset = $this->assetService->find($input['asset_id']);

        $pivotFrom = $this->assetRoomConditionService->findByAssetRoomCondition($input['asset_id'], $input['from_room_id'], $input['condition_id']);
        $pivotTo = $this->assetRoomConditionService->findByAssetRoomCondition($input['asset_id'], $input['to_room_id'], $input['condition_id']);


        $this->movementService->validation($asset, $pivotFrom, $input);

        $pivotFrom->update([
            'qty' => $pivotFrom->qty - $input['qty'],
        ]);

        if ($pivotTo) {
            $pivotTo->update(['qty' => $pivotTo->qty + $input['qty']]);
        } else {
            $this->assetRoomConditionService->create(['asset_id' => $input['asset_id'], 'room_id' => $input['to_room_id'], 'qty' => $input['qty'], 'condition_id' => $input['condition_id']]);
        }

        $asset->update(['last_move_date' => now()]);
        $this->movementService->create($input);
        return redirect()->route('admin.movements.index')->with('success', 'Movements successfully created');
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $results = $this->movementService->search($term);
        return response()->json($results);
    }

    public function edit($id)
    {
        $movement = $this->movementService->find($id);
        return view('movement.edit', compact('movement'));
    }

    public function update(MovementRequest $movementRequest, $id)
    {
        $input = $movementRequest->validated();
        $movement = $this->movementRepository->find($id);

        $asset = $movement->asset;
        $pivotFrom = $this->assetRoomConditionService->findByAssetRoomCondition($asset->id, $movement->from_room_id, $movement->condition_id);
        $pivotTo = $this->assetRoomConditionService->findByAssetRoomCondition($asset->id, $movement->to_room_id, $movement->condition_id);

        $pivotFrom->update([
            'qty' => $pivotFrom->qty + $movement->qty
        ]);
        $pivotTo->update([
            'qty' => $pivotTo->qty - $movement->qty
        ]);

        $newPivotFrom = $this->assetRoomConditionService->findByAssetRoomCondition($input['asset_id'], $input['from_room_id'], $input['condition_id']);

        $this->movementService->validation($asset, $newPivotFrom, $input);

        $newPivotFrom->update([
            'qty' => $newPivotFrom->qty - $input['qty'],
        ]);

        $newPivotTo = $this->assetRoomConditionService->findByAssetRoomCondition($input['asset_id'], $input['to_room_id'], $input['condition_id']);

        if ($newPivotTo) {
            $newPivotTo->update([
                'qty' => $newPivotTo->qty + $input['qty'],
            ]);
        } else {
            $this->assetRoomConditionService->create([
                'asset_id' => $input['asset_id'],
                'room_id' => $input['to_room_id'],
                'qty' => $input['qty'],
                'condition_id' => $input['condition_id']
            ]);
        }

        $this->movementService->update($id, $input);

        return redirect()->route('admin.movements.index')->with('success', 'Movement updated successfully!');
    }

    public function destroy($id)
    {
        $this->movementService->delete($id);
        return redirect()->route('admin.movements.index');
    }
}
