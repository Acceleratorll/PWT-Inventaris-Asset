<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Http\Requests\MovementRequest;
use App\Repositories\AssetTypeRepository;
use App\Repositories\RoomRepository;
use App\Services\AssetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetController extends Controller
{
    protected $assetService;
    protected $assetTypeRepositories;
    protected $roomRepositories;

    public function __construct(
        AssetService $assetService,
        AssetTypeRepository $assetTypeRepository,
        RoomRepository $roomRepository
    ) {
        $this->assetService = $assetService;
        $this->assetTypeRepositories = $assetTypeRepository;
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
        $asset = $asset->rooms()->get()->unique('id');
        return response()->json($asset);
    }

    public function create(): View
    {
        $types = $this->assetTypeRepositories->all();
        $rooms = $this->roomRepositories->all();
        return view('asset.create', compact(['types', 'rooms']));
    }

    public function store(AssetRequest $assetRequest)
    {
        $input = $assetRequest->validated();
        $checkTotal = $this->assetService->checkQty($input['qty_good'], $input['qty_bad'], $input['total']);
        if ($checkTotal === false) {
            return redirect()->back()->with('error', 'Pastikan memasukkan Total yang benar');
        }
        $this->assetService->create($input);
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
        $types = $this->assetTypeRepositories->all();
        $rooms = $this->roomRepositories->all();
        $asset = $this->assetService->find($id);
        return view('asset.edit', compact('asset', 'rooms', 'types'));
    }

    public function update(AssetRequest $assetRequest, $id)
    {
        $input = $assetRequest->validated();
        $this->assetService->update($id, $input);
        return redirect()->route('admin.assets.index')->with('success', 'Asset has been successfully updated!');
    }

    public function destroy($id)
    {
        $this->assetService->delete($id);
        return redirect()->route('admin.assets.index');
    }
}
