<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Models\AssetRoomCondition;
use App\Repositories\AssetTypeRepository;
use App\Repositories\RoomRepository;
use App\Services\AssetRoomConditionService;
use App\Services\AssetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetController extends Controller
{
    protected $assetService;
    protected $assetRoomConditionService;
    protected $roomRepositories;

    public function __construct(
        AssetService $assetService,
        AssetRoomConditionService $assetRoomConditionService,
        RoomRepository $roomRepository
    ) {
        $this->assetService = $assetService;
        $this->assetRoomConditionService = $assetRoomConditionService;
        $this->roomRepositories = $roomRepository;
    }

    public function index(): View
    {
        return view('asset.index');
    }

    public function tableAll()
    {
        return $this->assetService->tableAll();
    }

    public function selectAll(Request $request)
    {
        $term = $request->term;
        return $this->assetService->selectAll($term);
    }

    public function show($id): JsonResponse
    {
        $asset = $this->assetService->find($id);
        $asset = $asset->assetRoomConditions;
        return response()->json($asset);
    }

    public function create(): View
    {
        $rooms = $this->roomRepositories->all();
        return view('asset.create', compact(['rooms']));
    }

    public function store(AssetRequest $assetRequest)
    {
        $input = $assetRequest->validated();
        $checkTotal = $this->assetService->checkQty($input['qty_good'], $input['qty_bad'], $input['total']);
        if ($checkTotal === false) {
            return redirect()->back()->with('error', 'Pastikan memasukkan Total yang benar');
        }
        $asset = $this->assetService->create($input);
        foreach ($input['room_id'] as $roomId) {
            $this->assetRoomConditionService->create([
                'asset_id' => $asset->id,
                'room_id' => $roomId,
                'condition_id' => 1,
                'qty' => $input['qty_good'][$roomId],
            ]);

            $this->assetRoomConditionService->create([
                'asset_id' => $asset->id,
                'room_id' => $roomId,
                'condition_id' => 2,
                'qty' => $input['qty_bad'][$roomId],
            ]);
        }
        return redirect()->route('admin.assets.index')->with('success' . 'Asset berhasil dibuat');
    }

    public function search(Request $request): JsonResponse
    {
        $term = $request->input('term');
        $results = $this->assetService->search($term);
        return response()->json($results);
    }

    public function edit($id): View
    {
        $asset = $this->assetService->find($id);
        return view('asset.edit', compact('asset'));
    }

    public function update(AssetRequest $assetRequest, $id)
    {
        $input = $assetRequest->validated();

        if ($this->assetService->checkQty($input['qty_good'], $input['qty_bad'], $input['total'])) {
            return redirect()->back()->with('error', 'Pastikan memasukkan Total yang benar');
        }

        $oriData = $this->assetRoomConditionService->findByAsset($id)->pluck('room_id')->unique()->toArray();

        $diff = array_merge(array_diff($oriData, array_map('intval', $input['room_id'])), array_diff(array_map('intval', $input['room_id']), $oriData));

        if (!empty($diff)) {
            foreach ($diff as $roomId) {
                $item = $this->assetRoomConditionService->findByAssetRoom($id, $roomId);
                if ($item->count() > 0) {
                    $this->assetRoomConditionService->delete($item[0]->id);
                    $this->assetRoomConditionService->delete($item[1]->id);
                } else {
                    $this->assetRoomConditionService->create([
                        'asset_id' => $id,
                        'room_id' => $roomId,
                        'condition_id' => 1,
                        'qty' => $input['qty_good'][$roomId],
                    ]);

                    $this->assetRoomConditionService->create([
                        'asset_id' => $id,
                        'room_id' => $roomId,
                        'condition_id' => 2,
                        'qty' => $input['qty_bad'][$roomId],
                    ]);
                }
            }
        }


        foreach ($input['room_id'] as $roomId) {
            $good = $this->assetRoomConditionService->findByAssetRoomCondition($id, $roomId, 1);
            $this->assetRoomConditionService->update($good->id, [
                'asset_id' => $id,
                'room_id' => $roomId,
                'condition_id' => 1,
                'qty' => $input['qty_good'][$roomId],
            ]);

            $bad = $this->assetRoomConditionService->findByAssetRoomCondition($id, $roomId, 2);
            $this->assetRoomConditionService->update($bad->id, [
                'asset_id' => $id,
                'room_id' => $roomId,
                'condition_id' => 2,
                'qty' => $input['qty_bad'][$roomId],
            ]);
        }

        $this->assetService->update($id, $input);
        return redirect()->route('admin.assets.index')->with('success', 'Asset has been successfully updated!');
    }

    public function destroy($id)
    {
        $this->assetService->delete($id);
        return redirect()->route('admin.assets.index');
    }
}
