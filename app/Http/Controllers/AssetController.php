<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Repositories\AssetTypeRepository;
use App\Repositories\RoomRepository;
use App\Services\AssetService;
use Illuminate\Http\Request;

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

    public function index()
    {
        return view('asset.index');
    }

    public function tableAll()
    {
        return $this->assetService->tableAll();
    }

    public function create()
    {
        $types = $this->assetTypeRepositories->all();
        $rooms = $this->roomRepositories->all();
        return view('asset.create', compact(['types', 'rooms']));
    }

    public function store(AssetRequest $assetRequest)
    {
        $input = $assetRequest->validated();
        $this->assetService->create($input);
        return redirect()->route('admin.assets.index');
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $results = $this->assetService->search($term);
        return response()->json($results);
    }

    public function edit($id)
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

    public function delete($id)
    {
        $this->assetService->delete($id);
        return redirect()->route('admin.assets.index');
    }
}
